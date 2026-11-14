<?php

namespace App\Http\Controllers\Admin\SuperShop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuperShop\Branch;
use App\Models\SuperShop\PosCounter;
use App\Models\SuperShop\CashierShift;
use App\Models\SuperShop\CashDrawerTransaction;
use Illuminate\Support\Facades\Auth;

class ShiftManagementController extends Controller
{
    /**
     * Cashier shifts listing and history.
     */
    public function index(Request $request)
    {
        $branches = Branch::where('status', 'active')->get();
        $selectedBranchId = $request->get('branch_id');

        $query = CashierShift::with(['branch', 'counter', 'cashier', 'verifier'])->orderBy('id', 'desc');

        if ($selectedBranchId) {
            $query->where('branch_id', $selectedBranchId);
        }

        $shifts = $query->paginate(20)->withQueryString();

        return view('admin.supershop.shifts.index', compact('shifts', 'branches', 'selectedBranchId'));
    }

    /**
     * Open a new Cashier Shift.
     */
    public function openShift(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'opening_cash' => 'required|numeric|min:0',
        ]);

        $cashierId = Auth::id() ?: 1;

        // Check if there is already an open shift for this cashier
        $existing = CashierShift::where('branch_id', $request->branch_id)
            ->where('cashier_id', $cashierId)
            ->where('status', 'open')
            ->first();

        if ($existing) {
            return back()->with('info', 'আপনার ইতোমধ্যে একটি সক্রিয় শিফট চালু রয়েছে!');
        }

        $shift = CashierShift::create([
            'branch_id' => $request->branch_id,
            'pos_counter_id' => $request->pos_counter_id,
            'cashier_id' => $cashierId,
            'opened_at' => now(),
            'opening_cash' => (float)$request->opening_cash,
            'expected_cash' => (float)$request->opening_cash,
            'actual_cash' => 0,
            'cash_difference' => 0,
            'status' => 'open',
        ]);

        return back()->with('success', "ক্যাশ কাউন্টার শিফট সফলভাবে শুরু করা হয়েছে! (ওপেনিং ক্যাশ: ৳" . number_format($shift->opening_cash, 2) . ")");
    }

    /**
     * Close an existing Cashier Shift with Cash Difference Reconciliation.
     */
    public function closeShift(Request $request, $id)
    {
        $request->validate([
            'actual_cash' => 'required|numeric|min:0',
        ]);

        $shift = CashierShift::findOrFail($id);

        $actualCash = (float)$request->actual_cash;
        $expectedCash = (float)$shift->expected_cash;
        $diff = $actualCash - $expectedCash;

        $shift->update([
            'closed_at' => now(),
            'actual_cash' => $actualCash,
            'cash_difference' => $diff,
            'closing_notes' => $request->closing_notes,
            'verified_by' => Auth::id(),
            'status' => 'closed',
        ]);

        $msg = "শিফট সফলভাবে ক্লোজ করা হয়েছে! এক্সপেক্টেড ক্যাশ: ৳{$expectedCash}, প্রকৃত ক্যাশ: ৳{$actualCash}। ";
        if ($diff < 0) {
            $msg .= "পার্থক্য: ৳" . abs($diff) . " ঘাটতি (Shortage)।";
        } elseif ($diff > 0) {
            $msg .= "পার্থক্য: ৳" . $diff . " অতিরিক্ত (Overage)।";
        } else {
            $msg .= "হিসাব ১০০% মিলেছে (Balanced)।";
        }

        return back()->with('success', $msg);
    }

    /**
     * Record Cash In / Cash Out in drawer.
     */
    public function drawerTransaction(Request $request)
    {
        $request->validate([
            'shift_id' => 'required|exists:cashier_shifts,id',
            'type' => 'required|in:cash_in,cash_out,expense,refund',
            'amount' => 'required|numeric|min:1',
            'reason' => 'required|string',
        ]);

        $shift = CashierShift::findOrFail($request->shift_id);
        $amount = (float)$request->amount;

        CashDrawerTransaction::create([
            'shift_id' => $shift->id,
            'type' => $request->type,
            'amount' => $amount,
            'reason' => $request->reason,
            'user_id' => Auth::id(),
        ]);

        if ($request->type === 'cash_in') {
            $shift->expected_cash += $amount;
        } else {
            $shift->expected_cash -= $amount;
        }
        $shift->save();

        return back()->with('success', 'ক্যাশ ড্রয়ার লেনদেন সফলভাবে সংরক্ষণ করা হয়েছে!');
    }
}

