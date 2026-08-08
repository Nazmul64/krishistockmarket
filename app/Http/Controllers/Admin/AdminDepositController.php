<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Illuminate\Http\Request;

class AdminDepositController extends Controller
{
    public function index()
    {
        $deposits = Deposit::with('user')->latest()->get();
        return view('admin.deposit.index', compact('deposits'));
    }

    public function approve($id)
    {
        $deposit = Deposit::findOrFail($id);

        if ($deposit->status === 'approved') {
            return redirect()->back()->with('error', 'এই ডিপোজিটটি ইতিমধ্যেই অ্যাপ্রুভ করা হয়েছে!');
        }

        $deposit->update(['status' => 'approved']);

        // Ledger Rule #18 & Section #3: Previous Balance + Approved Deposit = New Balance
        RecordWalletLedger(
            $deposit->user_id,
            'Deposit',
            $deposit->deposit_amount,
            0,
            $deposit->payment_method,
            $deposit->trx_number
        );

        $notification = [
            'message' => 'ডিপোজিট সফলভাবে অ্যাপ্রুভ হয়েছে এবং কাস্টমার ওয়ালেটে ব্যালেন্স যোগ হয়েছে!',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    public function reject($id)
    {
        $deposit = Deposit::findOrFail($id);
        $deposit->update(['status' => 'rejected']);

        $notification = [
            'message' => 'ডিপোজিট রিকোয়েস্ট রিজেক্ট করা হয়েছে!',
            'alert-type' => 'warning'
        ];

        return redirect()->back()->with($notification);
    }
}
