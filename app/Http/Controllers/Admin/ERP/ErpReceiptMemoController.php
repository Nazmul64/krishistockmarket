<?php

namespace App\Http\Controllers\Admin\ERP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ERP\ErpMoneyReceipt;
use App\Models\ERP\ErpCashMemo;
use App\Models\ERP\ErpCashMemoItem;
use App\Models\ERP\ErpItem;
use App\Models\ERP\ErpCashBankAccount;
use App\Models\ERP\ErpCompanyProfile;
use App\Models\ERP\ErpStockTransaction;
use App\Models\ERP\ErpChartOfAccount;
use App\Models\ERP\ErpGeneralLedgerEntry;

class ErpReceiptMemoController extends Controller
{
    // ==========================================
    // 1. MONEY RECEIPTS (INCOME COLLECTION)
    // ==========================================
    public function moneyReceiptsIndex()
    {
        $receipts = ErpMoneyReceipt::with('bankAccount')->latest()->paginate(15);
        $bankAccounts = ErpCashBankAccount::where('is_active', true)->get();

        $totalReceiptsCount = ErpMoneyReceipt::count();
        $totalCollectedAmount = ErpMoneyReceipt::sum('received_amount');

        return view('admin.erp.billing.money_receipts', compact(
            'receipts',
            'bankAccounts',
            'totalReceiptsCount',
            'totalCollectedAmount'
        ));
    }

    public function storeMoneyReceipt(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'payment_purpose' => 'required|string|max:255',
            'received_amount' => 'required|numeric|min:1',
            'receipt_date' => 'required|date',
            'bank_account_id' => 'required|exists:erp_cash_bank_accounts,id',
        ]);

        $receiptNo = 'MR-' . date('Y') . '-' . rand(1000, 9999);
        $amount = $request->received_amount;
        $bankAccount = ErpCashBankAccount::findOrFail($request->bank_account_id);

        DB::beginTransaction();
        try {
            $mr = ErpMoneyReceipt::create([
                'receipt_number' => $receiptNo,
                'receipt_date' => $request->receipt_date,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'payment_purpose' => $request->payment_purpose,
                'payment_method' => $request->payment_method ?? 'cash',
                'bank_account_id' => $bankAccount->id,
                'received_amount' => $amount,
                'previous_due' => $request->previous_due ?? 0,
                'remaining_due' => $request->remaining_due ?? 0,
                'amount_in_words' => $request->amount_in_words ?? number_format($amount, 2) . ' Taka Only',
                'received_by' => auth()->user()->name ?? 'Accounts Officer',
                'remarks' => $request->remarks,
            ]);

            // Update Bank / Cash Account balance
            $bankAccount->increment('current_balance', $amount);

            // Post to Income Ledger
            $salesAcc = ErpChartOfAccount::where('account_code', '4100')->first() ?? ErpChartOfAccount::first();
            $bankAssetAcc = ErpChartOfAccount::where('account_code', '1120')->first() ?? ErpChartOfAccount::first();

            ErpGeneralLedgerEntry::create([
                'transaction_date' => $request->receipt_date,
                'account_id' => $bankAssetAcc->id,
                'reference_type' => 'money_receipt',
                'reference_number' => $receiptNo,
                'debit' => $amount,
                'credit' => 0,
                'balance_after' => $bankAccount->current_balance,
                'description' => "Money Receipt from {$request->customer_name} ({$request->payment_purpose})",
            ]);

            ErpGeneralLedgerEntry::create([
                'transaction_date' => $request->receipt_date,
                'account_id' => $salesAcc->id,
                'reference_type' => 'money_receipt',
                'reference_number' => $receiptNo,
                'debit' => 0,
                'credit' => $amount,
                'balance_after' => $salesAcc->current_balance + $amount,
                'description' => "Income Collected from {$request->customer_name}",
            ]);

            DB::commit();
            return redirect()->back()->with('success', "মানি রিসিট ({$receiptNo}) সফলভাবে তৈরি ও ব্যাংক একাউন্টে জমা হয়েছে।");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'রিসিট তৈরিতে ব্যর্থ: ' . $e->getMessage());
        }
    }

    public function printMoneyReceipt($id)
    {
        $receipt = ErpMoneyReceipt::with('bankAccount')->findOrFail($id);
        $company = ErpCompanyProfile::first() ?? new ErpCompanyProfile();

        return view('admin.erp.billing.money_receipt_print', compact('receipt', 'company'));
    }

    // ==========================================
    // 2. CASH MEMOS (POS & DIRECT SALES)
    // ==========================================
    public function cashMemosIndex()
    {
        $memos = ErpCashMemo::with(['items', 'bankAccount'])->latest()->paginate(15);
        $items = ErpItem::where('is_active', true)->where('current_stock', '>', 0)->get();
        $bankAccounts = ErpCashBankAccount::where('is_active', true)->get();

        $totalSalesCount = ErpCashMemo::count();
        $totalSalesRevenue = ErpCashMemo::sum('grand_total');

        return view('admin.erp.billing.cash_memos', compact(
            'memos',
            'items',
            'bankAccounts',
            'totalSalesCount',
            'totalSalesRevenue'
        ));
    }

    public function storeCashMemo(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'memo_date' => 'required|date',
            'item_ids' => 'required|array',
            'quantities' => 'required|array',
            'unit_prices' => 'required|array',
            'bank_account_id' => 'required|exists:erp_cash_bank_accounts,id',
        ]);

        DB::beginTransaction();
        try {
            $memoNo = 'CM-' . date('Ymd') . '-' . rand(100, 999);
            $subtotal = 0;

            // Verify stocks first
            foreach ($request->item_ids as $idx => $itemId) {
                $qty = floatval($request->quantities[$idx] ?? 1);
                $item = ErpItem::find($itemId);
                if ($item && $item->current_stock < $qty) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "পর্যাপ্ত স্টক নেই: {$item->name} (মজুত: {$item->current_stock}, বিক্রি চাওয়া হয়েছে: {$qty})");
                }
            }

            foreach ($request->item_ids as $idx => $itemId) {
                $qty = floatval($request->quantities[$idx] ?? 1);
                $price = floatval($request->unit_prices[$idx] ?? 0);
                $subtotal += ($qty * $price);
            }

            $discount = floatval($request->discount ?? 0);
            $vat = floatval($request->vat ?? 0);
            $grandTotal = max(0, ($subtotal - $discount) + $vat);
            $paid = floatval($request->paid_amount ?? $grandTotal);
            $due = max(0, $grandTotal - $paid);

            $bankAccount = ErpCashBankAccount::findOrFail($request->bank_account_id);

            $cashMemo = ErpCashMemo::create([
                'memo_number' => $memoNo,
                'memo_date' => $request->memo_date,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'vat' => $vat,
                'grand_total' => $grandTotal,
                'paid_amount' => $paid,
                'due_amount' => $due,
                'payment_method' => $request->payment_method ?? 'cash',
                'bank_account_id' => $bankAccount->id,
                'sales_person_name' => auth()->user()->name ?? 'Sales Officer',
                'status' => $due == 0 ? 'paid' : 'partial',
                'remarks' => $request->remarks,
            ]);

            // Save line items and DEDUCT stock
            foreach ($request->item_ids as $idx => $itemId) {
                $qty = floatval($request->quantities[$idx] ?? 1);
                $price = floatval($request->unit_prices[$idx] ?? 0);
                $lineSub = $qty * $price;

                $item = ErpItem::find($itemId);
                $itemName = $item ? $item->name : 'Item #' . $itemId;

                ErpCashMemoItem::create([
                    'cash_memo_id' => $cashMemo->id,
                    'item_id' => $itemId,
                    'item_name' => $itemName,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'subtotal' => $lineSub,
                    'discount' => 0,
                    'total' => $lineSub,
                ]);

                if ($item) {
                    $item->decrement('current_stock', $qty);

                    // Record Stock Out Transaction
                    ErpStockTransaction::create([
                        'transaction_number' => 'STX-SALE-' . time() . '-' . $item->id,
                        'transaction_type' => 'stock_out',
                        'item_id' => $item->id,
                        'warehouse_id' => $item->default_warehouse_id,
                        'quantity' => $qty,
                        'unit_price' => $price,
                        'total_amount' => $lineSub,
                        'reference_no' => $memoNo,
                        'reference_type' => 'cash_memo_sale',
                        'purpose' => "নগদে বিক্রয় (Cash Memo: {$memoNo})",
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            // Update cash/bank account balance with paid amount
            if ($paid > 0) {
                $bankAccount->increment('current_balance', $paid);
            }

            // Post to Sales Ledger
            $salesAcc = ErpChartOfAccount::where('account_code', '4100')->first() ?? ErpChartOfAccount::first();
            $cashAcc = ErpChartOfAccount::where('account_code', '1110')->first() ?? ErpChartOfAccount::first();

            ErpGeneralLedgerEntry::create([
                'transaction_date' => $request->memo_date,
                'account_id' => $cashAcc->id,
                'reference_type' => 'cash_memo',
                'reference_number' => $memoNo,
                'debit' => $paid,
                'credit' => 0,
                'balance_after' => $bankAccount->current_balance,
                'description' => "Cash Memo Sale to {$request->customer_name}",
            ]);

            ErpGeneralLedgerEntry::create([
                'transaction_date' => $request->memo_date,
                'account_id' => $salesAcc->id,
                'reference_type' => 'cash_memo',
                'reference_number' => $memoNo,
                'debit' => 0,
                'credit' => $paid,
                'balance_after' => $salesAcc->current_balance + $paid,
                'description' => "Sales Revenue Cash Memo #{$memoNo}",
            ]);

            DB::commit();
            return redirect()->back()->with('success', "ক্যাশ মেমো ({$memoNo}) সফলভাবে তৈরি হয়েছে, স্টক কাটা হয়েছে এবং বিক্রয় রাজস্ব যুক্ত হয়েছে।");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'ক্যাশ মেমো ব্যর্থ: ' . $e->getMessage());
        }
    }

    public function printCashMemo($id)
    {
        $memo = ErpCashMemo::with(['items.item', 'bankAccount'])->findOrFail($id);
        $company = ErpCompanyProfile::first() ?? new ErpCompanyProfile();

        return view('admin.erp.billing.cash_memo_print', compact('memo', 'company'));
    }
}

