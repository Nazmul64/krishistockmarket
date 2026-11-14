<?php

namespace App\Http\Controllers\Admin\ERP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ERP\ErpBill;
use App\Models\ERP\ErpBillItem;
use App\Models\ERP\ErpCashBankAccount;
use App\Models\ERP\ErpVoucher;
use App\Models\ERP\ErpVoucherEntry;
use App\Models\ERP\ErpGeneralLedgerEntry;
use App\Models\ERP\ErpChartOfAccount;

class ErpBillingController extends Controller
{
    public function index(Request $request)
    {
        $query = ErpBill::with('items');

        if ($request->filled('bill_type')) {
            $query->where('bill_type', $request->bill_type);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        $bills = $query->latest()->paginate(15);
        $bankAccounts = ErpCashBankAccount::where('is_active', true)->get();

        $totalBillsCount = ErpBill::count();
        $totalBillAmount = ErpBill::sum('total_amount');
        $totalPaidAmount = ErpBill::sum('paid_amount');
        $totalDueAmount = ErpBill::sum('due_amount');
        $pendingApprovalCount = ErpBill::where('approval_status', 'pending_approval')->count();

        return view('admin.erp.billing.bills', compact(
            'bills',
            'bankAccounts',
            'totalBillsCount',
            'totalBillAmount',
            'totalPaidAmount',
            'totalDueAmount',
            'pendingApprovalCount'
        ));
    }

    public function storeBill(Request $request)
    {
        $request->validate([
            'vendor_name' => 'required|string|max:255',
            'bill_type' => 'required|string',
            'bill_date' => 'required|date',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $subtotal = $request->subtotal;
        $discount = $request->discount ?? 0;
        $vatTax = $request->vat_tax ?? 0;
        $total = ($subtotal - $discount) + $vatTax;
        $paid = $request->paid_amount ?? 0;
        $due = max(0, $total - $paid);

        $payStatus = 'unpaid';
        if ($paid >= $total && $total > 0) {
            $payStatus = 'paid';
        } elseif ($paid > 0) {
            $payStatus = 'partially_paid';
        }

        $billNo = 'BILL-' . date('Ymd') . '-' . rand(100, 999);

        $bill = new ErpBill();
        $bill->bill_number = $billNo;
        $bill->bill_type = $request->bill_type;
        $bill->vendor_name = $request->vendor_name;
        $bill->department_name = $request->department_name;
        $bill->reference_no = $request->reference_no;
        $bill->bill_date = $request->bill_date;
        $bill->due_date = $request->due_date;
        $bill->subtotal = $subtotal;
        $bill->discount = $discount;
        $bill->vat_tax = $vatTax;
        $bill->total_amount = $total;
        $bill->paid_amount = $paid;
        $bill->due_amount = $due;
        $bill->payment_status = $payStatus;
        $bill->approval_status = $request->approval_status ?? 'pending_approval';
        $bill->description = $request->description;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = 'bill_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/erp/bills'), $filename);
            $bill->attachment = 'upload/erp/bills/' . $filename;
        }

        $bill->save();

        return redirect()->back()->with('success', "বিল সফলভাবে দাখিল করা হয়েছে (বিল নং: {$billNo})।");
    }

    public function approveBill(Request $request, $id)
    {
        $bill = ErpBill::findOrFail($id);
        $bill->update([
            'approval_status' => 'approved',
            'approved_by' => auth()->user()->name ?? 'Administrator',
        ]);

        return redirect()->back()->with('success', "বিল {$bill->bill_number} অনুমোদিত হয়েছে।");
    }

    public function payBill(Request $request, $id)
    {
        $bill = ErpBill::findOrFail($id);

        $request->validate([
            'pay_amount' => 'required|numeric|min:1|max:' . $bill->due_amount,
            'bank_account_id' => 'required|exists:erp_cash_bank_accounts,id',
            'payment_method' => 'required|string',
        ]);

        $payAmount = $request->pay_amount;
        $bankAccount = ErpCashBankAccount::findOrFail($request->bank_account_id);

        if ($bankAccount->current_balance < $payAmount) {
            return redirect()->back()->with('error', "নির্বাচিত একাউন্টে পর্যাপ্ত ব্যালেন্স নেই! বর্তমান ব্যালেন্স: ৳" . number_format($bankAccount->current_balance, 2));
        }

        DB::beginTransaction();
        try {
            // Update bill paid and due
            $newPaid = $bill->paid_amount + $payAmount;
            $newDue = max(0, $bill->total_amount - $newPaid);
            $payStatus = $newDue == 0 ? 'paid' : 'partially_paid';

            $bill->update([
                'paid_amount' => $newPaid,
                'due_amount' => $newDue,
                'payment_status' => $payStatus,
            ]);

            // Deduct bank account balance
            $bankAccount->decrement('current_balance', $payAmount);

            // Generate Payment Voucher (PV)
            $pvNo = 'PV-' . date('Y') . '-' . rand(1000, 9999);
            $pv = ErpVoucher::create([
                'voucher_number' => $pvNo,
                'voucher_type' => 'payment',
                'voucher_date' => now()->toDateString(),
                'payee_or_payer' => $bill->vendor_name,
                'payment_method' => $request->payment_method,
                'bank_account_id' => $bankAccount->id,
                'amount' => $payAmount,
                'amount_in_words' => number_format($payAmount, 2) . ' Taka Only',
                'reference_bill_id' => $bill->id,
                'reference_no' => $bill->bill_number,
                'description' => "বিল পরিশোধ: {$bill->bill_number} ({$bill->vendor_name})",
                'status' => 'posted',
                'prepared_by' => auth()->user()->name ?? 'Accountant',
                'approved_by' => auth()->user()->name ?? 'Finance Head',
            ]);

            // Double Entry Accounting
            $payableAcc = ErpChartOfAccount::where('account_code', '2110')->first() ?? ErpChartOfAccount::first();
            $bankAssetAcc = ErpChartOfAccount::where('account_code', '1120')->first() ?? ErpChartOfAccount::first();

            ErpVoucherEntry::create([
                'voucher_id' => $pv->id,
                'account_id' => $payableAcc->id,
                'debit' => $payAmount,
                'credit' => 0,
                'description' => "Bill Payment {$bill->bill_number}",
            ]);

            ErpVoucherEntry::create([
                'voucher_id' => $pv->id,
                'account_id' => $bankAssetAcc->id,
                'debit' => 0,
                'credit' => $payAmount,
                'description' => "Deducted from {$bankAccount->account_name}",
            ]);

            // Post to General Ledger
            ErpGeneralLedgerEntry::create([
                'transaction_date' => now()->toDateString(),
                'voucher_id' => $pv->id,
                'account_id' => $payableAcc->id,
                'reference_type' => 'bill_payment',
                'reference_number' => $pvNo,
                'debit' => $payAmount,
                'credit' => 0,
                'balance_after' => 0,
                'description' => "Bill Payment to {$bill->vendor_name} ({$bill->bill_number})",
            ]);

            ErpGeneralLedgerEntry::create([
                'transaction_date' => now()->toDateString(),
                'voucher_id' => $pv->id,
                'account_id' => $bankAssetAcc->id,
                'reference_type' => 'bill_payment',
                'reference_number' => $pvNo,
                'debit' => 0,
                'credit' => $payAmount,
                'balance_after' => $bankAccount->current_balance,
                'description' => "Paid via {$bankAccount->account_name}",
            ]);

            DB::commit();
            return redirect()->back()->with('success', "বিল বাবদ ৳" . number_format($payAmount, 2) . " সফলভাবে পরিশোধ হয়েছে এবং পেমেন্ট ভাউচার ({$pvNo}) তৈরি হয়েছে।");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'পেমেন্ট ব্যর্থ: ' . $e->getMessage());
        }
    }
}

