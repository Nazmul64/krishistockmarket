<?php

namespace App\Http\Controllers\Admin\ERP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ERP\ErpVoucher;
use App\Models\ERP\ErpVoucherEntry;
use App\Models\ERP\ErpGeneralLedgerEntry;
use App\Models\ERP\ErpChartOfAccount;
use App\Models\ERP\ErpCashBankAccount;
use App\Models\ERP\ErpCompanyProfile;

class ErpVoucherController extends Controller
{
    public function index(Request $request)
    {
        $query = ErpVoucher::with(['entries.account', 'bankAccount']);

        if ($request->filled('voucher_type')) {
            $query->where('voucher_type', $request->voucher_type);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('voucher_number', 'like', "%$s%")
                  ->orWhere('payee_or_payer', 'like', "%$s%")
                  ->orWhere('description', 'like', "%$s%");
            });
        }

        $vouchers = $query->latest()->paginate(15);
        $accounts = ErpChartOfAccount::where('is_active', true)->orderBy('account_code')->get();
        $bankAccounts = ErpCashBankAccount::where('is_active', true)->get();

        $totalPaymentVouchers = ErpVoucher::where('voucher_type', 'payment')->sum('amount');
        $totalReceiptVouchers = ErpVoucher::where('voucher_type', 'receipt')->sum('amount');
        $totalJournalVouchers = ErpVoucher::where('voucher_type', 'journal')->sum('amount');
        $totalContraVouchers = ErpVoucher::where('voucher_type', 'contra')->sum('amount');

        return view('admin.erp.vouchers.index', compact(
            'vouchers',
            'accounts',
            'bankAccounts',
            'totalPaymentVouchers',
            'totalReceiptVouchers',
            'totalJournalVouchers',
            'totalContraVouchers'
        ));
    }

    public function storeVoucher(Request $request)
    {
        $request->validate([
            'voucher_type' => 'required|in:payment,receipt,journal,contra,adjustment',
            'voucher_date' => 'required|date',
            'payee_or_payer' => 'required|string',
            'account_ids' => 'required|array',
            'debits' => 'required|array',
            'credits' => 'required|array',
        ]);

        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($request->account_ids as $idx => $accId) {
            $d = floatval($request->debits[$idx] ?? 0);
            $c = floatval($request->credits[$idx] ?? 0);
            $totalDebit += $d;
            $totalCredit += $c;
        }

        // Validate double entry equality
        if (abs($totalDebit - $totalCredit) > 0.01) {
            return redirect()->back()->with('error', "ভাউচারের মোট ডেবিট (৳" . number_format($totalDebit, 2) . ") এবং মোট ক্রেডিট (৳" . number_format($totalCredit, 2) . ") অবশ্যই সমান হতে হবে!");
        }

        if ($totalDebit <= 0) {
            return redirect()->back()->with('error', 'ভাউচারের মোট টাকার পরিমাণ শূন্য হতে পারবে না।');
        }

        DB::beginTransaction();
        try {
            $prefixMap = [
                'payment' => 'PV',
                'receipt' => 'RV',
                'journal' => 'JV',
                'contra' => 'CV',
                'adjustment' => 'AV',
            ];
            $p = $prefixMap[$request->voucher_type] ?? 'VO';
            $voucherNo = $p . '-' . date('Y') . '-' . rand(1000, 9999);

            $voucher = ErpVoucher::create([
                'voucher_number' => $voucherNo,
                'voucher_type' => $request->voucher_type,
                'voucher_date' => $request->voucher_date,
                'payee_or_payer' => $request->payee_or_payer,
                'payment_method' => $request->payment_method ?? 'cash',
                'bank_account_id' => $request->bank_account_id,
                'amount' => $totalDebit,
                'amount_in_words' => $request->amount_in_words ?? number_format($totalDebit, 2) . ' Taka Only',
                'reference_no' => $request->reference_no,
                'description' => $request->description,
                'status' => 'posted',
                'prepared_by' => auth()->user()->name ?? 'Accountant',
                'approved_by' => auth()->user()->name ?? 'Finance Head',
            ]);

            // Save line entries & post to general ledger
            foreach ($request->account_ids as $idx => $accId) {
                $d = floatval($request->debits[$idx] ?? 0);
                $c = floatval($request->credits[$idx] ?? 0);
                $desc = $request->descriptions[$idx] ?? $request->description;

                ErpVoucherEntry::create([
                    'voucher_id' => $voucher->id,
                    'account_id' => $accId,
                    'debit' => $d,
                    'credit' => $c,
                    'description' => $desc,
                ]);

                // Update Account balance
                $acc = ErpChartOfAccount::find($accId);
                if ($acc) {
                    if ($acc->normal_balance === 'debit') {
                        $acc->current_balance += ($d - $c);
                    } else {
                        $acc->current_balance += ($c - $d);
                    }
                    $acc->save();

                    ErpGeneralLedgerEntry::create([
                        'transaction_date' => $request->voucher_date,
                        'voucher_id' => $voucher->id,
                        'account_id' => $acc->id,
                        'reference_type' => $request->voucher_type . '_voucher',
                        'reference_number' => $voucherNo,
                        'debit' => $d,
                        'credit' => $c,
                        'balance_after' => $acc->current_balance,
                        'description' => $desc,
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            // If cash/bank account involved, update its balance
            if ($request->bank_account_id) {
                $bankAcc = ErpCashBankAccount::find($request->bank_account_id);
                if ($bankAcc) {
                    if ($request->voucher_type === 'payment') {
                        $bankAcc->decrement('current_balance', $totalDebit);
                    } elseif ($request->voucher_type === 'receipt') {
                        $bankAcc->increment('current_balance', $totalDebit);
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', "ভাউচার ({$voucherNo}) সফলভাবে তৈরি ও জেনারেলে লেজারে পোস্ট করা হয়েছে।");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'ভাউচার তৈরিতে ব্যর্থ: ' . $e->getMessage());
        }
    }

    public function printVoucher($id)
    {
        $voucher = ErpVoucher::with(['entries.account', 'bankAccount'])->findOrFail($id);
        $company = ErpCompanyProfile::first() ?? new ErpCompanyProfile();

        return view('admin.erp.vouchers.print', compact('voucher', 'company'));
    }
}

