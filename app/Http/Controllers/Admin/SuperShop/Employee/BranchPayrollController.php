<?php

namespace App\Http\Controllers\Admin\SuperShop\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\SuperShop\Branch;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeePayroll;
use App\Models\Employee\EmployeePayrollItem;
use App\Models\Employee\EmployeeSalaryPayment;
use App\Models\Employee\EmployeeSalaryAdvance;
use App\Models\Employee\EmployeeAdvanceAdjustment;
use App\Models\Employee\EmployeeAttendance;
use App\Models\Employee\EmployeeActivityLog;
use App\Services\BranchSecurityService;

class BranchPayrollController extends Controller
{
    /**
     * Payroll List & Monthly Run Hub (Section 15)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = BranchSecurityService::isAdmin($user);
        $userBranchId = BranchSecurityService::getUserBranchId($user);

        $selectedBranchId = $isAdmin ? ($request->get('branch_id') ?: Branch::where('status', 'active')->value('id')) : $userBranchId;
        BranchSecurityService::enforceBranchAccess($selectedBranchId);

        $query = EmployeePayroll::with(['branch', 'processedByUser'])
            ->orderBy('salary_month', 'desc');
        $query = BranchSecurityService::applyBranchScope($query, 'branch_id', $user);

        $payrolls = $query->paginate(15)->withQueryString();

        $branches = $isAdmin ? Branch::where('status', 'active')->get() : collect();

        return view('admin.supershop.employees.payroll.index', [
            'payrolls' => $payrolls,
            'selectedBranchId' => $selectedBranchId,
            'branches' => $branches,
            'isAdmin' => $isAdmin,
            'userBranchId' => $userBranchId,
        ]);
    }

    /**
     * View Payroll Details & Employee Breakdown
     */
    public function show($id)
    {
        $payroll = EmployeePayroll::with(['branch', 'items.employee'])->findOrFail($id);
        BranchSecurityService::enforceBranchAccess($payroll->branch_id);

        return view('admin.supershop.employees.payroll.show', [
            'payroll' => $payroll,
        ]);
    }

    /**
     * Generate / Process Monthly Payroll for a Branch (Section 15)
     */
    public function generate(Request $request)
    {
        $user = Auth::user();
        $isAdmin = BranchSecurityService::isAdmin($user);
        $userBranchId = BranchSecurityService::getUserBranchId($user);

        $branchId = $isAdmin ? ($request->input('branch_id') ?: $userBranchId) : $userBranchId;
        BranchSecurityService::enforceBranchAccess($branchId);

        $salaryMonth = $request->input('salary_month', Carbon::today()->format('Y-m'));

        // Check if payroll already exists for this branch and month
        $existing = EmployeePayroll::where('branch_id', $branchId)->where('salary_month', $salaryMonth)->first();
        if ($existing && $existing->status === 'closed') {
            return back()->with('error', "{$salaryMonth} মাসের পে-রোল ইতিমধ্যে পরিশোধ ও সম্পন্ন (Closed) করা হয়েছে।");
        }

        DB::beginTransaction();
        try {
            $employees = Employee::where('branch_id', $branchId)
                ->where('is_active', true)
                ->get();

            if ($employees->isEmpty()) {
                return back()->with('error', 'এই ব্রাঞ্চে কোনো সক্রিয় কর্মচারী পাওয়া যায়নি।');
            }

            $payrollCode = 'PAY-BR' . sprintf('%02d', $branchId) . '-' . str_replace('-', '', $salaryMonth);

            $payroll = EmployeePayroll::updateOrCreate(
                [
                    'branch_id' => $branchId,
                    'salary_month' => $salaryMonth,
                ],
                [
                    'payroll_code' => $payrollCode,
                    'total_employees' => $employees->count(),
                    'status' => 'draft',
                    'processed_by' => $user->id,
                    'processed_at' => now(),
                    'notes' => $request->input('notes'),
                ]
            );

            // Delete old draft items if regenerating
            EmployeePayrollItem::where('payroll_id', $payroll->id)->delete();

            $totalGross = 0;
            $totalAllowances = 0;
            $totalOvertime = 0;
            $totalAdvanceDeductions = 0;
            $totalOtherDeductions = 0;
            $totalNetPayable = 0;

            $monthStart = Carbon::parse($salaryMonth . '-01')->startOfMonth()->format('Y-m-d');
            $monthEnd = Carbon::parse($salaryMonth . '-01')->endOfMonth()->format('Y-m-d');

            foreach ($employees as $emp) {
                // Calculate Attendance Metrics
                $presentDays = EmployeeAttendance::where('employee_id', $emp->id)->whereBetween('date', [$monthStart, $monthEnd])->where('status', 'present')->count();
                $absentDays  = EmployeeAttendance::where('employee_id', $emp->id)->whereBetween('date', [$monthStart, $monthEnd])->where('status', 'absent')->count();
                $lateDays    = EmployeeAttendance::where('employee_id', $emp->id)->whereBetween('date', [$monthStart, $monthEnd])->where('status', 'late')->count();
                $leaveDays   = EmployeeAttendance::where('employee_id', $emp->id)->whereBetween('date', [$monthStart, $monthEnd])->where('status', 'leave')->count();

                // Salary Components
                $basicSalary = (float) $emp->basic_salary;
                $houseRent = (float) $emp->house_rent;
                $medical = (float) $emp->medical_allowance;
                $transport = (float) $emp->transport_allowance;
                $food = (float) $emp->food_allowance;
                $other = (float) $emp->other_allowance;
                $allowances = $houseRent + $medical + $transport + $food + $other;

                // Overtime
                $overtimeMins = (int) EmployeeAttendance::where('employee_id', $emp->id)->whereBetween('date', [$monthStart, $monthEnd])->sum('overtime_minutes');
                $overtimeHours = round($overtimeMins / 60, 1);
                $overtimeAmount = round($overtimeHours * (float)$emp->overtime_rate, 2);

                // Deductions: 1 day basic salary per unapproved absent
                $dailyRate = $emp->monthly_salary > 0 ? ($emp->monthly_salary / 30) : 0;
                $absentDeduction = round($absentDays * $dailyRate, 2);
                $lateDeduction = ($lateDays >= 3) ? round(($lateDays / 3) * ($dailyRate * 0.5), 2) : 0;

                // Salary Advance Adjustment (Section 10.4)
                // Find pending advance for this employee
                $pendingAdvance = EmployeeSalaryAdvance::where('employee_id', $emp->id)
                    ->whereIn('status', ['approved', 'disbursed', 'partially_adjusted'])
                    ->where('remaining_amount', '>', 0)
                    ->first();

                $advanceDeduction = 0;
                if ($pendingAdvance) {
                    $installment = (float) $pendingAdvance->installment_amount;
                    $advanceDeduction = min($installment > 0 ? $installment : $pendingAdvance->remaining_amount, $pendingAdvance->remaining_amount);
                }

                $grossSalary = $basicSalary + $allowances + $overtimeAmount + (float)$emp->bonus;
                $netPayable = max(0, $grossSalary - ($absentDeduction + $lateDeduction + $advanceDeduction));

                EmployeePayrollItem::create([
                    'payroll_id' => $payroll->id,
                    'employee_id' => $emp->id,
                    'branch_id' => $branchId,
                    'basic_salary' => $basicSalary,
                    'house_rent' => $houseRent,
                    'medical_allowance' => $medical,
                    'transport_allowance' => $transport,
                    'food_allowance' => $food,
                    'other_allowance' => $other,
                    'overtime_hours' => $overtimeHours,
                    'overtime_rate' => $emp->overtime_rate,
                    'overtime_amount' => $overtimeAmount,
                    'bonus' => $emp->bonus,
                    'commission' => $emp->commission,
                    'total_present_days' => $presentDays,
                    'total_absent_days' => $absentDays,
                    'total_late_days' => $lateDays,
                    'total_leave_days' => $leaveDays,
                    'absent_deduction' => $absentDeduction,
                    'late_deduction' => $lateDeduction,
                    'advance_adjustment_amount' => $advanceDeduction,
                    'other_deductions' => 0,
                    'gross_salary' => $grossSalary,
                    'net_payable' => $netPayable,
                    'paid_amount' => 0,
                    'payment_status' => 'pending',
                ]);

                $totalGross += $grossSalary;
                $totalAllowances += $allowances;
                $totalOvertime += $overtimeAmount;
                $totalAdvanceDeductions += $advanceDeduction;
                $totalOtherDeductions += ($absentDeduction + $lateDeduction);
                $totalNetPayable += $netPayable;
            }

            $payroll->update([
                'total_gross' => $totalGross,
                'total_allowances' => $totalAllowances,
                'total_overtime' => $totalOvertime,
                'total_advance_deductions' => $totalAdvanceDeductions,
                'total_other_deductions' => $totalOtherDeductions,
                'total_net_payable' => $totalNetPayable,
                'total_pending' => $totalNetPayable,
                'total_paid' => 0,
                'status' => 'generated',
            ]);

            EmployeeActivityLog::log(
                'Payroll Generated',
                'payroll',
                $branchId,
                null,
                "মাসিক বেতন বিবরণী প্রস্তুত: {$salaryMonth} মাসের জন্য {$employees->count()} জন স্টাফের মোট ৳" . number_format($totalNetPayable, 2) . " টাকা পে-রোল তৈরি করা হয়েছে।"
            );

            DB::commit();

            return redirect()->route('admin.supershop.employee_system.payroll.show', $payroll->id)
                ->with('success', "{$salaryMonth} মাসের পে-রোল সফলভাবে জেনারেট হয়েছে! মোট প্রদেয় বেতন: ৳" . number_format($totalNetPayable, 2));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'পে-রোল জেনারেট ব্যর্থ: ' . $e->getMessage());
        }
    }

    /**
     * Record Salary Payment for an Employee (Section 15 & 10.4)
     */
    public function recordPayment(Request $request)
    {
        $request->validate([
            'payroll_item_id' => 'required|exists:employee_payroll_items,id',
            'paid_amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
        ]);

        $item = EmployeePayrollItem::with('payroll', 'employee')->findOrFail($request->input('payroll_item_id'));
        $payroll = $item->payroll;
        BranchSecurityService::enforceBranchAccess($payroll->branch_id);

        $paidAmount = (float) $request->input('paid_amount');
        $item->paid_amount += $paidAmount;
        $item->payment_method = $request->input('payment_method', 'cash');
        $item->paid_at = now();

        if ($item->paid_amount >= $item->net_payable) {
            $item->payment_status = 'paid';
        } else {
            $item->payment_status = 'partially_paid';
        }
        $item->save();

        // Create Payment History Record
        $paymentNo = 'SLP-BR' . sprintf('%02d', $payroll->branch_id) . '-' . date('Ym') . '-' . rand(100, 999);
        $receiptNo = 'REC-' . strtoupper(Str::random(8));

        EmployeeSalaryPayment::create([
            'payment_no' => $paymentNo,
            'employee_id' => $item->employee_id,
            'branch_id' => $payroll->branch_id,
            'payroll_id' => $payroll->id,
            'salary_month' => $payroll->salary_month,
            'basic_salary' => $item->basic_salary,
            'allowance_total' => ($item->house_rent + $item->medical_allowance + $item->transport_allowance + $item->food_allowance + $item->other_allowance),
            'bonus' => $item->bonus,
            'overtime_amount' => $item->overtime_amount,
            'advance_adjusted' => $item->advance_adjustment_amount,
            'deductions' => ($item->absent_deduction + $item->late_deduction + $item->other_deductions),
            'net_salary' => $item->net_payable,
            'paid_amount' => $paidAmount,
            'payment_date' => $request->input('payment_date'),
            'payment_method' => $request->input('payment_method'),
            'transaction_ref' => $request->input('transaction_ref'),
            'paid_by' => Auth::id(),
            'status' => ($item->payment_status === 'paid') ? 'paid' : 'partially_paid',
            'receipt_no' => $receiptNo,
            'notes' => $request->input('notes'),
        ]);

        // If advance adjustment was included in this item, adjust advance record (Section 10.4)
        if ($item->advance_adjustment_amount > 0) {
            $adv = EmployeeSalaryAdvance::where('employee_id', $item->employee_id)
                ->whereIn('status', ['approved', 'disbursed', 'partially_adjusted'])
                ->where('remaining_amount', '>', 0)
                ->first();

            if ($adv) {
                $adjAmt = min($item->advance_adjustment_amount, $adv->remaining_amount);
                $newAdj = $adv->adjusted_amount + $adjAmt;
                $newRem = max(0, $adv->advance_amount - $newAdj);
                $advSt = ($newRem == 0) ? 'fully_adjusted' : 'partially_adjusted';

                $adv->update([
                    'adjusted_amount' => $newAdj,
                    'remaining_amount' => $newRem,
                    'status' => $advSt,
                ]);

                EmployeeAdvanceAdjustment::create([
                    'salary_advance_id' => $adv->id,
                    'employee_id' => $item->employee_id,
                    'branch_id' => $payroll->branch_id,
                    'payroll_id' => $payroll->id,
                    'adjustment_date' => $request->input('payment_date'),
                    'adjustment_month' => $payroll->salary_month,
                    'adjusted_amount' => $adjAmt,
                    'remaining_balance_after' => $newRem,
                    'adjusted_by' => Auth::id(),
                    'remarks' => "মাসিক বেতন পে-রোল ({$payroll->salary_month}) সমন্বয়",
                ]);
            }
        }

        // Recompute payroll totals
        $newTotalPaid = $payroll->items()->sum('paid_amount');
        $newTotalPending = max(0, $payroll->total_net_payable - $newTotalPaid);
        $payroll->total_paid = $newTotalPaid;
        $payroll->total_pending = $newTotalPending;
        $payroll->status = ($newTotalPending == 0) ? 'paid' : 'approved';
        $payroll->save();

        EmployeeActivityLog::log(
            'Salary Paid',
            'salary',
            $payroll->branch_id,
            $item->employee_id,
            "বেতন প্রদান: {$item->employee->name}-কে {$payroll->salary_month} মাসের বেতন ৳" . number_format($paidAmount, 2) . " পরিশোধ করা হয়েছে (রশিদ: {$receiptNo})"
        );

        return back()->with('success', "কর্মচারী '{$item->employee->name}'-এর বেতন ৳" . number_format($paidAmount, 2) . " সফলভাবে পরিশোধ করা হয়েছে!");
    }

    /**
     * View / Print Payslip (Section 15)
     */
    public function payslip($itemId)
    {
        $item = EmployeePayrollItem::with(['payroll.branch', 'employee'])->findOrFail($itemId);
        BranchSecurityService::enforceBranchAccess($item->branch_id);

        return view('admin.supershop.employees.payroll.payslip', [
            'item' => $item,
        ]);
    }
}
