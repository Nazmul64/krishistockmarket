<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\HrmAttendance;
use App\Models\HRM\HrmEmployeeLoan;
use App\Models\HRM\HrmEmployeeProfile;
use App\Models\HRM\HrmPayroll;
use App\Models\HRM\HrmPayrollItem;
use App\Models\HRM\HrmSalaryAdvance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HrmPayrollController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->month_year ?? date('Y-m');

        $payroll = HrmPayroll::where('month_year', $selectedMonth)->first();
        $payrollItems = [];

        if ($payroll) {
            $payrollItems = HrmPayrollItem::with(['user.hrmProfile.department', 'user.hrmProfile.designation'])
                ->where('payroll_id', $payroll->id)
                ->get();
        }

        $allPayrolls = HrmPayroll::latest()->get();

        return view('admin.hrm.payroll.index', compact('payroll', 'payrollItems', 'allPayrolls', 'selectedMonth'));
    }

    public function generatePayroll(Request $request)
    {
        $request->validate([
            'month_year' => 'required|string',
        ]);

        $monthYear = $request->month_year;

        $payroll = HrmPayroll::firstOrCreate(
            ['month_year' => $monthYear],
            [
                'status' => 'generated',
                'processed_by' => Auth::id(),
            ]
        );

        $employees = HrmEmployeeProfile::with('user')->where('status', 'active')->get();

        $totalBasic = 0;
        $totalAllowances = 0;
        $totalDeductions = 0;
        $totalNet = 0;

        foreach ($employees as $emp) {
            $basic = $emp->basic_salary ?? 0;
            $allowances = ($emp->house_rent ?? 0) + ($emp->medical_allowance ?? 0) + ($emp->transport_allowance ?? 0) + ($emp->other_allowance ?? 0);

            // Calculate absent deduction
            $absentDays = HrmAttendance::where('user_id', $emp->user_id)
                ->where('date', 'like', "{$monthYear}%")
                ->where('status', 'absent')
                ->count();

            $perDaySalary = $basic / 30;
            $absentDeduction = round($absentDays * $perDaySalary, 2);

            // Loan deduction
            $loan = HrmEmployeeLoan::where('user_id', $emp->user_id)->where('status', 'running')->first();
            $loanDeduction = $loan ? min($loan->monthly_deduction, $loan->remaining_amount) : 0;

            // Salary Advance deduction
            $advance = HrmSalaryAdvance::where('user_id', $emp->user_id)
                ->where('deduction_month', $monthYear)
                ->where('status', 'approved')
                ->first();
            $advanceDeduction = $advance ? $advance->amount : 0;

            $gross = $basic + $allowances;
            $deductions = $absentDeduction + $loanDeduction + $advanceDeduction;
            $net = max(0, $gross - $deductions);

            HrmPayrollItem::updateOrCreate(
                [
                    'payroll_id' => $payroll->id,
                    'user_id' => $emp->user_id,
                ],
                [
                    'basic_salary' => $basic,
                    'allowances' => $allowances,
                    'overtime_amount' => 0,
                    'bonus' => 0,
                    'late_deduction' => 0,
                    'absent_deduction' => $absentDeduction,
                    'loan_deduction' => $loanDeduction,
                    'advance_deduction' => $advanceDeduction,
                    'gross_salary' => $gross,
                    'net_salary' => $net,
                    'status' => 'unpaid',
                    'payment_method' => $emp->payment_method ?? 'bank',
                ]
            );

            $totalBasic += $basic;
            $totalAllowances += $allowances;
            $totalDeductions += $deductions;
            $totalNet += $net;
        }

        $payroll->update([
            'total_employees' => count($employees),
            'total_basic' => $totalBasic,
            'total_allowances' => $totalAllowances,
            'total_deductions' => $totalDeductions,
            'total_net' => $totalNet,
            'status' => 'generated',
            'processed_by' => Auth::id(),
        ]);

        return back()->with('success', "{$monthYear} মাসের পে-রোল সফলভাবে জেনারেট করা হয়েছে।");
    }

    public function markPaid($itemId)
    {
        $item = HrmPayrollItem::findOrFail($itemId);
        $item->update([
            'status' => 'paid',
            'payment_date' => date('Y-m-d'),
        ]);

        return back()->with('success', 'বেতন পরিশোধ হিসেবে মার্ক করা হয়েছে।');
    }

    public function showPayslip($itemId)
    {
        $item = HrmPayrollItem::with(['payroll', 'user.hrmProfile.department', 'user.hrmProfile.designation', 'user.hrmProfile.branch'])->findOrFail($itemId);

        return view('admin.hrm.payroll.payslip', compact('item'));
    }
}
