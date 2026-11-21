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
use App\Models\Employee\EmployeeLeaveRequest;
use App\Models\Employee\AttendanceDevice;
use App\Services\BranchSecurityService;

class BranchEmployeeDashboardController extends Controller
{
    /**
     * Central Employee Monitoring Dashboard (Section 11 & 7)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = BranchSecurityService::isAdmin($user);
        $userBranchId = BranchSecurityService::getUserBranchId($user);

        // Branch Managers are strictly restricted to their own branch
        if (!$isAdmin) {
            if (!$userBranchId) {
                return redirect()->route('admin.dashboard')->with('error', 'আপনার সাথে কোনো নির্দিষ্ট ব্রাঞ্চ সংযুক্ত নেই।');
            }
            return $this->branchManagerDashboard($request, $userBranchId);
        }

        // --- MAIN ADMIN CENTRAL DASHBOARD ---
        $today = Carbon::today()->format('Y-m-d');
        $thisMonth = Carbon::today()->format('Y-m');

        // Total Branches
        $branches = Branch::where('status', 'active')->orderBy('id', 'asc')->get();
        $totalBranches = $branches->count();

        // 11.1 Admin Dashboard KPIs
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('is_active', true)->where('employment_status', 'active')->count();

        $presentToday = EmployeeAttendance::where('date', $today)->where('status', 'present')->count();
        $absentToday  = EmployeeAttendance::where('date', $today)->where('status', 'absent')->count();
        $lateToday    = EmployeeAttendance::where('date', $today)->where('status', 'late')->count();
        $onLeaveToday = EmployeeAttendance::where('date', $today)->where('status', 'leave')->count();

        $totalMonthlyPayroll = (float) Employee::where('is_active', true)->sum('monthly_salary');
        $salaryPaidThisMonth = (float) EmployeeSalaryPayment::where('salary_month', $thisMonth)->sum('paid_amount');
        $salaryPendingThisMonth = max(0, $totalMonthlyPayroll - $salaryPaidThisMonth);

        $totalAdvanceGiven = (float) EmployeeSalaryAdvance::whereIn('status', ['approved', 'disbursed', 'partially_adjusted', 'fully_adjusted'])->sum('advance_amount');
        $totalAdvanceAdjusted = (float) EmployeeSalaryAdvance::sum('adjusted_amount');
        $totalAdvanceOutstanding = (float) EmployeeSalaryAdvance::whereIn('status', ['approved', 'disbursed', 'partially_adjusted'])->sum('remaining_amount');

        $totalDevices = AttendanceDevice::where('is_active', true)->count();
        $offlineDevices = AttendanceDevice::where('is_active', true)->where('status', 'disconnected')->count();

        // 11.2 Branch-wise Employee Summary Table
        $branchSummaries = [];
        foreach ($branches as $branch) {
            $bEmpCount = Employee::where('branch_id', $branch->id)->where('is_active', true)->count();
            $bPresent  = EmployeeAttendance::where('branch_id', $branch->id)->where('date', $today)->where('status', 'present')->count();
            $bAbsent   = EmployeeAttendance::where('branch_id', $branch->id)->where('date', $today)->where('status', 'absent')->count();
            $bLate     = EmployeeAttendance::where('branch_id', $branch->id)->where('date', $today)->where('status', 'late')->count();
            $bSalary   = (float) Employee::where('branch_id', $branch->id)->where('is_active', true)->sum('monthly_salary');
            $bAdvance  = (float) EmployeeSalaryAdvance::where('branch_id', $branch->id)->whereIn('status', ['approved', 'disbursed', 'partially_adjusted'])->sum('remaining_amount');

            // Today's Branch Sales (Section 3: Main Admin can view all branches sales)
            $bTodaySales = (float) PosSale::where('branch_id', $branch->id)
                ->whereDate('sale_time', $today)
                ->where('sale_status', 'completed')
                ->sum('grand_total');

            $branchSummaries[] = [
                'branch' => $branch,
                'employees' => $bEmpCount,
                'present' => $bPresent,
                'absent' => $bAbsent,
                'late' => $bLate,
                'monthly_salary' => $bSalary,
                'advance' => $bAdvance,
                'today_sales' => $bTodaySales,
            ];
        }

        // All Branches Total Sales Today (Section 3 Example)
        $allBranchesTotalSalesToday = (float) PosSale::whereDate('sale_time', $today)
            ->where('sale_status', 'completed')
            ->sum('grand_total');

        return view('admin.supershop.employees.dashboard', [
            'isAdmin' => true,
            'currentBranch' => null,
            'totalBranches' => $totalBranches,
            'totalEmployees' => $totalEmployees,
            'activeEmployees' => $activeEmployees,
            'presentToday' => $presentToday,
            'absentToday' => $absentToday,
            'lateToday' => $lateToday,
            'onLeaveToday' => $onLeaveToday,
            'totalMonthlyPayroll' => $totalMonthlyPayroll,
            'salaryPaid' => $salaryPaidThisMonth,
            'salaryPending' => $salaryPendingThisMonth,
            'totalAdvanceGiven' => $totalAdvanceGiven,
            'totalAdvanceAdjusted' => $totalAdvanceAdjusted,
            'totalAdvanceOutstanding' => $totalAdvanceOutstanding,
            'totalDevices' => $totalDevices,
            'offlineDevices' => $offlineDevices,
            'branchSummaries' => $branchSummaries,
            'allBranchesTotalSalesToday' => $allBranchesTotalSalesToday,
        ]);
    }

    /**
     * Branch Manager Dedicated Employee Dashboard (Section 7 & 14)
     */
    protected function branchManagerDashboard(Request $request, int $branchId)
    {
        $branch = Branch::findOrFail($branchId);
        $today = Carbon::today()->format('Y-m-d');
        $thisMonth = Carbon::today()->format('Y-m');

        // Section 7.1 Employee Dashboard Summary
        $totalEmployees  = Employee::where('branch_id', $branchId)->count();
        $activeEmployees = Employee::where('branch_id', $branchId)->where('is_active', true)->where('employment_status', 'active')->count();

        $presentToday = EmployeeAttendance::where('branch_id', $branchId)->where('date', $today)->where('status', 'present')->count();
        $absentToday  = EmployeeAttendance::where('branch_id', $branchId)->where('date', $today)->where('status', 'absent')->count();
        $lateToday    = EmployeeAttendance::where('branch_id', $branchId)->where('date', $today)->where('status', 'late')->count();
        $onLeaveToday = EmployeeAttendance::where('branch_id', $branchId)->where('date', $today)->where('status', 'leave')->count();

        $totalMonthlySalary = (float) Employee::where('branch_id', $branchId)->where('is_active', true)->sum('monthly_salary');
        $salaryPaid = (float) EmployeeSalaryPayment::where('branch_id', $branchId)->where('salary_month', $thisMonth)->sum('paid_amount');
        $salaryPending = max(0, $totalMonthlySalary - $salaryPaid);

        $totalAdvance = (float) EmployeeSalaryAdvance::where('branch_id', $branchId)->whereIn('status', ['approved', 'disbursed', 'partially_adjusted', 'fully_adjusted'])->sum('advance_amount');
        $advanceOutstanding = (float) EmployeeSalaryAdvance::where('branch_id', $branchId)->whereIn('status', ['approved', 'disbursed', 'partially_adjusted'])->sum('remaining_amount');

        // Own Branch Sales Only (Section 3 Privacy Rule)
        $branchTodaySales = (float) PosSale::where('branch_id', $branchId)
            ->whereDate('sale_time', $today)
            ->where('sale_status', 'completed')
            ->sum('grand_total');

        $recentAttendances = EmployeeAttendance::with('employee')
            ->where('branch_id', $branchId)
            ->where('date', $today)
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        $recentAdvances = EmployeeSalaryAdvance::with('employee')
            ->where('branch_id', $branchId)
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        $device = AttendanceDevice::where('branch_id', $branchId)->where('is_active', true)->first();

        return view('admin.supershop.employees.dashboard', [
            'isAdmin' => false,
            'currentBranch' => $branch,
            'totalEmployees' => $totalEmployees,
            'activeEmployees' => $activeEmployees,
            'presentToday' => $presentToday,
            'absentToday' => $absentToday,
            'lateToday' => $lateToday,
            'onLeaveToday' => $onLeaveToday,
            'totalMonthlySalary' => $totalMonthlySalary,
            'salaryPaid' => $salaryPaid,
            'salaryPending' => $salaryPending,
            'totalAdvance' => $totalAdvance,
            'advanceOutstanding' => $advanceOutstanding,
            'branchTodaySales' => $branchTodaySales,
            'recentAttendances' => $recentAttendances,
            'recentAdvances' => $recentAdvances,
            'device' => $device,
        ]);
    }
}
