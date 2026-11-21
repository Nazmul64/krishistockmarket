<?php

namespace App\Http\Controllers\Admin\SuperShop\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\SuperShop\Branch;
use App\Models\SuperShop\PosSale;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeAttendance;
use App\Models\Employee\EmployeeSalaryAdvance;
use App\Models\Employee\EmployeeSalaryPayment;
use App\Services\BranchSecurityService;

class BranchEmployeeReportController extends Controller
{
    /**
     * Comprehensive Reports Center (Section 13 & 3)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = BranchSecurityService::isAdmin($user);
        $userBranchId = BranchSecurityService::getUserBranchId($user);

        $reportType = $request->get('type', 'attendance'); // attendance, advance, salary, sales
        $dateFilter = $request->get('filter', 'this_month');

        $now = Carbon::now();
        $startDate = match ($dateFilter) {
            'today' => Carbon::today()->startOfDay(),
            'yesterday' => Carbon::yesterday()->startOfDay(),
            'this_week' => Carbon::today()->startOfWeek(),
            'last_week' => Carbon::today()->subWeek()->startOfWeek(),
            'this_month' => Carbon::today()->startOfMonth(),
            'last_month' => Carbon::today()->subMonth()->startOfMonth(),
            'this_year' => Carbon::today()->startOfYear(),
            'custom' => $request->filled('from_date') ? Carbon::parse($request->get('from_date'))->startOfDay() : Carbon::today()->startOfMonth(),
            default => Carbon::today()->startOfMonth(),
        };

        $endDate = match ($dateFilter) {
            'today' => Carbon::today()->endOfDay(),
            'yesterday' => Carbon::yesterday()->endOfDay(),
            'this_week' => Carbon::today()->endOfWeek(),
            'last_week' => Carbon::today()->subWeek()->endOfWeek(),
            'last_month' => Carbon::today()->subMonth()->endOfMonth(),
            'this_year' => Carbon::today()->endOfYear(),
            'custom' => $request->filled('to_date') ? Carbon::parse($request->get('to_date'))->endOfDay() : Carbon::today()->endOfDay(),
            default => Carbon::today()->endOfDay(),
        };

        $branches = $isAdmin ? Branch::where('status', 'active')->get() : Branch::where('id', $userBranchId)->get();

        $selectedBranchId = $isAdmin ? $request->get('branch_id') : $userBranchId;

        // 1. Attendance Report
        $attendanceData = [];
        if ($reportType === 'attendance') {
            $empQuery = Employee::where('is_active', true);
            if ($selectedBranchId) {
                $empQuery->where('branch_id', $selectedBranchId);
            }
            $employees = $empQuery->orderBy('name', 'asc')->get();

            foreach ($employees as $emp) {
                $pCount = EmployeeAttendance::where('employee_id', $emp->id)->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])->where('status', 'present')->count();
                $lCount = EmployeeAttendance::where('employee_id', $emp->id)->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])->where('status', 'late')->count();
                $aCount = EmployeeAttendance::where('employee_id', $emp->id)->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])->where('status', 'absent')->count();
                $lvCount = EmployeeAttendance::where('employee_id', $emp->id)->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])->where('status', 'leave')->count();

                $attendanceData[] = [
                    'employee' => $emp,
                    'present' => $pCount,
                    'late' => $lCount,
                    'absent' => $aCount,
                    'leave' => $lvCount,
                    'total_working_days' => ($pCount + $lCount + $aCount + $lvCount),
                ];
            }
        }

        // 2. Advance Report
        $advanceData = [];
        if ($reportType === 'advance') {
            $advQuery = EmployeeSalaryAdvance::with(['employee', 'branch'])
                ->whereBetween('advance_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

            $advQuery = BranchSecurityService::applyBranchScope($advQuery, 'branch_id', $user);
            $advanceData = $advQuery->orderBy('id', 'desc')->get();
        }

        // 3. Salary Report
        $salaryData = [];
        if ($reportType === 'salary') {
            $salQuery = EmployeeSalaryPayment::with(['employee', 'branch'])
                ->whereBetween('payment_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

            $salQuery = BranchSecurityService::applyBranchScope($salQuery, 'branch_id', $user);
            $salaryData = $salQuery->orderBy('id', 'desc')->get();
        }

        // 4. Branch Sales Privacy & Performance (Section 3)
        $salesData = [];
        if ($reportType === 'sales') {
            if ($isAdmin) {
                // Main Admin sees all branches sales comparison
                foreach ($branches as $branch) {
                    $bSales = (float) PosSale::where('branch_id', $branch->id)
                        ->whereBetween('sale_time', [$startDate, $endDate])
                        ->where('sale_status', 'completed')
                        ->sum('grand_total');

                    $bOrders = PosSale::where('branch_id', $branch->id)
                        ->whereBetween('sale_time', [$startDate, $endDate])
                        ->where('sale_status', 'completed')
                        ->count();

                    $salesData[] = [
                        'branch' => $branch,
                        'total_sales' => $bSales,
                        'orders_count' => $bOrders,
                    ];
                }
            } else {
                // Branch Manager sees ONLY their own branch sales
                $myBranch = Branch::find($userBranchId);
                if ($myBranch) {
                    $bSales = (float) PosSale::where('branch_id', $userBranchId)
                        ->whereBetween('sale_time', [$startDate, $endDate])
                        ->where('sale_status', 'completed')
                        ->sum('grand_total');

                    $bOrders = PosSale::where('branch_id', $userBranchId)
                        ->whereBetween('sale_time', [$startDate, $endDate])
                        ->where('sale_status', 'completed')
                        ->count();

                    $salesData[] = [
                        'branch' => $myBranch,
                        'total_sales' => $bSales,
                        'orders_count' => $bOrders,
                    ];
                }
            }
        }

        return view('admin.supershop.employees.reports.index', [
            'reportType' => $reportType,
            'dateFilter' => $dateFilter,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branches' => $branches,
            'selectedBranchId' => $selectedBranchId,
            'attendanceData' => $attendanceData,
            'advanceData' => $advanceData,
            'salaryData' => $salaryData,
            'salesData' => $salesData,
            'isAdmin' => $isAdmin,
            'userBranchId' => $userBranchId,
        ]);
    }
}
