<?php

namespace App\Http\Controllers\Admin\SuperShop\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\SuperShop\Branch;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeSalaryAdvance;
use App\Models\Employee\EmployeeAdvanceAdjustment;
use App\Models\Employee\EmployeeActivityLog;
use App\Services\BranchSecurityService;

class BranchSalaryAdvanceController extends Controller
{
    /**
     * Branch Salary Advances List (Section 10)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = BranchSecurityService::isAdmin($user);
        $userBranchId = BranchSecurityService::getUserBranchId($user);

        $query = EmployeeSalaryAdvance::with(['employee', 'branch', 'approvedByUser'])
            ->orderBy('id', 'desc');

        // Enforce strict branch isolation (Section 10.2)
        $query = BranchSecurityService::applyBranchScope($query, 'branch_id', $user);

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('month')) {
            $query->where('adjustment_month', $request->get('month'));
        }

        $advances = $query->paginate(20)->withQueryString();

        $employeesQuery = Employee::where('is_active', true);
        $employeesQuery = BranchSecurityService::applyBranchScope($employeesQuery, 'branch_id', $user);
        $employees = $employeesQuery->orderBy('name', 'asc')->get();

        $branches = $isAdmin ? Branch::where('status', 'active')->get() : collect();

        // Calculate summary cards for this branch / view
        $summaryQuery = EmployeeSalaryAdvance::query();
        $summaryQuery = BranchSecurityService::applyBranchScope($summaryQuery, 'branch_id', $user);

        $totalGiven = (clone $summaryQuery)->whereIn('status', ['approved', 'disbursed', 'partially_adjusted', 'fully_adjusted'])->sum('advance_amount');
        $totalAdjusted = (clone $summaryQuery)->sum('adjusted_amount');
        $totalRemaining = (clone $summaryQuery)->whereIn('status', ['approved', 'disbursed', 'partially_adjusted'])->sum('remaining_amount');

        return view('admin.supershop.employees.advances.index', [
            'advances' => $advances,
            'employees' => $employees,
            'branches' => $branches,
            'isAdmin' => $isAdmin,
            'userBranchId' => $userBranchId,
            'totalGiven' => $totalGiven,
            'totalAdjusted' => $totalAdjusted,
            'totalRemaining' => $totalRemaining,
        ]);
    }

    /**
     * Store New Salary Advance (Section 10.1)
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $isAdmin = BranchSecurityService::isAdmin($user);
        $userBranchId = BranchSecurityService::getUserBranchId($user);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'advance_amount' => 'required|numeric|min:1',
            'advance_date' => 'required|date',
            'payment_method' => 'required|string',
        ]);

        $employee = Employee::findOrFail($request->input('employee_id'));
        $branchId = $employee->branch_id;

        // Security check: Manager can only record advance for own branch
        BranchSecurityService::enforceBranchAccess($branchId);

        $amount = (float) $request->input('advance_amount');
        $installment = (float) $request->input('installment_amount', $amount);
        if ($installment <= 0) $installment = $amount;

        // Generate Unique Advance Number
        $advanceNo = 'ADV-BR' . sprintf('%02d', $branchId) . '-' . date('Ym') . '-' . rand(100, 999);

        $advance = EmployeeSalaryAdvance::create([
            'advance_no' => $advanceNo,
            'employee_id' => $employee->id,
            'branch_id' => $branchId,
            'advance_date' => $request->input('advance_date'),
            'advance_amount' => $amount,
            'reason' => $request->input('reason', 'বেতন অগ্রিম (Salary Advance)'),
            'payment_method' => $request->input('payment_method', 'cash'),
            'paid_by' => $user->id,
            'approved_by' => $user->id,
            'adjustment_month' => $request->input('adjustment_month', Carbon::today()->addMonth()->format('Y-m')),
            'installment_amount' => $installment,
            'remaining_amount' => $amount,
            'adjusted_amount' => 0,
            'status' => 'disbursed',
            'remarks' => $request->input('remarks'),
        ]);

        EmployeeActivityLog::log(
            'Salary Advance Created',
            'advance',
            $branchId,
            $employee->id,
            "বেতন অগ্রিম প্রদান: {$employee->name}-কে ৳" . number_format($amount, 2) . " টাকা প্রদান করা হয়েছে। (ভাউচার: {$advance->advance_no})"
        );

        return back()->with('success', "কর্মচারী '{$employee->name}'-এর জন্য ৳" . number_format($amount, 2) . " অগ্রিম সফলভাবে রেকর্ড করা হয়েছে!");
    }

    /**
     * Record Manual Partial Advance Adjustment (Section 10.3)
     */
    public function adjust(Request $request, $id)
    {
        $advance = EmployeeSalaryAdvance::findOrFail($id);
        BranchSecurityService::enforceBranchAccess($advance->branch_id);

        $request->validate([
            'adjusted_amount' => 'required|numeric|min:1|max:' . $advance->remaining_amount,
            'adjustment_date' => 'required|date',
        ]);

        $adjAmount = (float) $request->input('adjusted_amount');
        $newAdjusted = $advance->adjusted_amount + $adjAmount;
        $newRemaining = max(0, $advance->advance_amount - $newAdjusted);
        $newStatus = ($newRemaining == 0) ? 'fully_adjusted' : 'partially_adjusted';

        DB::beginTransaction();
        try {
            $advance->update([
                'adjusted_amount' => $newAdjusted,
                'remaining_amount' => $newRemaining,
                'status' => $newStatus,
            ]);

            EmployeeAdvanceAdjustment::create([
                'salary_advance_id' => $advance->id,
                'employee_id' => $advance->employee_id,
                'branch_id' => $advance->branch_id,
                'adjustment_date' => $request->input('adjustment_date'),
                'adjustment_month' => Carbon::parse($request->input('adjustment_date'))->format('Y-m'),
                'adjusted_amount' => $adjAmount,
                'remaining_balance_after' => $newRemaining,
                'adjusted_by' => Auth::id(),
                'remarks' => $request->input('remarks', 'হাতে হাতে সমন্বয় (Direct adjustment)'),
            ]);

            EmployeeActivityLog::log(
                'Salary Advance Adjusted',
                'advance',
                $advance->branch_id,
                $advance->employee_id,
                "অগ্রিম সমন্বয়: {$advance->advance_no} হতে ৳" . number_format($adjAmount, 2) . " সমন্বয় করা হয়েছে। অবশিষ্ট: ৳" . number_format($newRemaining, 2)
            );

            DB::commit();

            return back()->with('success', "অগ্রিম টাকা হতে ৳" . number_format($adjAmount, 2) . " সফলভাবে সমন্বয় করা হয়েছে!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'সমন্বয়ে ত্রুটি: ' . $e->getMessage());
        }
    }

    /**
     * Central All Branch Advance Report (Section 12: Main Admin Only)
     */
    public function centralReport(Request $request)
    {
        $user = Auth::user();
        if (!BranchSecurityService::isAdmin($user)) {
            abort(403, 'এই কেন্দ্রীয় অগ্রিম রিপোর্টটি শুধুমাত্র মেইন এডমিনের জন্য সংরক্ষিত।');
        }

        $today = Carbon::today()->format('Y-m-d');
        $thisMonth = Carbon::today()->format('Y-m');

        // Section 12 Advance Summary Cards
        $totalAdvanceGiven = (float) EmployeeSalaryAdvance::whereIn('status', ['approved', 'disbursed', 'partially_adjusted', 'fully_adjusted'])->sum('advance_amount');
        $advanceGivenToday = (float) EmployeeSalaryAdvance::where('advance_date', $today)->sum('advance_amount');
        $advanceGivenThisMonth = (float) EmployeeSalaryAdvance::where('advance_date', 'like', "{$thisMonth}%")->sum('advance_amount');
        $totalAdvanceAdjusted = (float) EmployeeSalaryAdvance::sum('adjusted_amount');
        $totalOutstandingAdvance = (float) EmployeeSalaryAdvance::whereIn('status', ['approved', 'disbursed', 'partially_adjusted'])->sum('remaining_amount');
        $totalEmployeesWithAdvance = EmployeeSalaryAdvance::where('remaining_amount', '>', 0)->distinct('employee_id')->count('employee_id');

        // Query with filters
        $query = EmployeeSalaryAdvance::with(['employee', 'branch']);

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->get('branch_id'));
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->get('employee_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('outstanding_only') && $request->get('outstanding_only') == '1') {
            $query->where('remaining_amount', '>', 0);
        }

        if ($request->filled('from_date')) {
            $query->where('advance_date', '>=', $request->get('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->where('advance_date', '<=', $request->get('to_date'));
        }

        $advances = $query->orderBy('id', 'desc')->paginate(30)->withQueryString();

        $branches = Branch::where('status', 'active')->get();
        $employees = Employee::where('is_active', true)->orderBy('name', 'asc')->get();

        // Branch-wise Advance Aggregations (Section 12 Table)
        $branchAggregates = [];
        foreach ($branches as $branch) {
            $bGiven = (float) EmployeeSalaryAdvance::where('branch_id', $branch->id)->whereIn('status', ['approved', 'disbursed', 'partially_adjusted', 'fully_adjusted'])->sum('advance_amount');
            $bAdj   = (float) EmployeeSalaryAdvance::where('branch_id', $branch->id)->sum('adjusted_amount');
            $bRem   = (float) EmployeeSalaryAdvance::where('branch_id', $branch->id)->whereIn('status', ['approved', 'disbursed', 'partially_adjusted'])->sum('remaining_amount');
            $bEmpCount = EmployeeSalaryAdvance::where('branch_id', $branch->id)->where('remaining_amount', '>', 0)->distinct('employee_id')->count('employee_id');

            $branchAggregates[] = [
                'branch' => $branch,
                'total_given' => $bGiven,
                'adjusted' => $bAdj,
                'remaining' => $bRem,
                'employees_count' => $bEmpCount,
            ];
        }

        return view('admin.supershop.employees.advances.report', [
            'advances' => $advances,
            'branches' => $branches,
            'employees' => $employees,
            'branchAggregates' => $branchAggregates,
            'totalAdvanceGiven' => $totalAdvanceGiven,
            'advanceGivenToday' => $advanceGivenToday,
            'advanceGivenThisMonth' => $advanceGivenThisMonth,
            'totalAdvanceAdjusted' => $totalAdvanceAdjusted,
            'totalOutstandingAdvance' => $totalOutstandingAdvance,
            'totalEmployeesWithAdvance' => $totalEmployeesWithAdvance,
        ]);
    }
}
