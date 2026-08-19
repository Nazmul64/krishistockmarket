<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\HrmEmployeeLoan;
use App\Models\HRM\HrmSalaryAdvance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HrmLoanAdvanceController extends Controller
{
    public function index()
    {
        $loans = HrmEmployeeLoan::with(['user', 'approver'])->latest()->get();
        $advances = HrmSalaryAdvance::with(['user', 'approver'])->latest()->get();
        $employees = User::where('role', 'employee')->get();

        return view('admin.hrm.loans.index', compact('loans', 'advances', 'employees'));
    }

    public function storeLoan(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'loan_amount' => 'required|numeric|min:1',
            'monthly_deduction' => 'required|numeric|min:1',
            'loan_type' => 'nullable|string',
        ]);

        HrmEmployeeLoan::create([
            'user_id' => $request->user_id,
            'loan_type' => $request->loan_type ?? 'personal',
            'loan_amount' => $request->loan_amount,
            'monthly_deduction' => $request->monthly_deduction,
            'remaining_amount' => $request->loan_amount,
            'status' => 'running',
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'লোন সফলভাবে বরাদ্দ করা হয়েছে।');
    }

    public function storeAdvance(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'deduction_month' => 'required|string',
            'reason' => 'nullable|string',
        ]);

        HrmSalaryAdvance::create([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'deduction_month' => $request->deduction_month,
            'reason' => $request->reason,
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'স্যালারি এডভান্স সফলভাবে মঞ্জুর করা হয়েছে।');
    }
}
