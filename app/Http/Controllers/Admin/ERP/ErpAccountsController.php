<?php

namespace App\Http\Controllers\Admin\ERP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ERP\ErpChartOfAccount;
use App\Models\ERP\ErpGeneralLedgerEntry;
use App\Models\ERP\ErpCashBankAccount;
use App\Models\ERP\ErpVoucher;

class ErpAccountsController extends Controller
{
    // ==========================================
    // 1. CHART OF ACCOUNTS (COA)
    // ==========================================
    public function chartOfAccounts()
    {
        $accounts = ErpChartOfAccount::with(['parent', 'children'])->orderBy('account_code')->get();
        $parentAccounts = ErpChartOfAccount::whereNull('parent_id')->orWhere('is_system', true)->get();

        $totalAssets = ErpChartOfAccount::where('root_type', 'asset')->sum('current_balance');
        $totalLiabilities = ErpChartOfAccount::where('root_type', 'liability')->sum('current_balance');
        $totalEquity = ErpChartOfAccount::where('root_type', 'equity')->sum('current_balance');
        $totalIncome = ErpChartOfAccount::where('root_type', 'income')->sum('current_balance');
        $totalExpenses = ErpChartOfAccount::where('root_type', 'expense')->sum('current_balance');

        return view('admin.erp.accounts.chart_of_accounts', compact(
            'accounts',
            'parentAccounts',
            'totalAssets',
            'totalLiabilities',
            'totalEquity',
            'totalIncome',
            'totalExpenses'
        ));
    }

    public function storeAccount(Request $request)
    {
        $request->validate([
            'account_code' => 'required|string|unique:erp_chart_of_accounts,account_code',
            'account_name' => 'required|string|max:255',
            'root_type' => 'required|in:asset,liability,equity,income,expense',
            'normal_balance' => 'required|in:debit,credit',
        ]);

        ErpChartOfAccount::create([
            'account_code' => $request->account_code,
            'account_name' => $request->account_name,
            'account_name_bn' => $request->account_name_bn,
            'parent_id' => $request->parent_id,
            'root_type' => $request->root_type,
            'normal_balance' => $request->normal_balance,
            'opening_balance' => $request->opening_balance ?? 0,
            'current_balance' => $request->opening_balance ?? 0,
            'is_system' => false,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'নতুন লেজার অ্যাকাউন্ট সফলভাবে চার্ট অব অ্যাকাউন্টসে যুক্ত হয়েছে।');
    }

    // ==========================================
    // 2. GENERAL LEDGER
    // ==========================================
    public function generalLedger(Request $request)
    {
        $accounts = ErpChartOfAccount::where('is_active', true)->orderBy('account_code')->get();
        $selectedAccount = null;
        $ledgerEntries = collect();
        $openingBalance = 0;

        if ($request->filled('account_id')) {
            $selectedAccount = ErpChartOfAccount::findOrFail($request->account_id);
            $query = ErpGeneralLedgerEntry::where('account_id', $selectedAccount->id);

            if ($request->filled('start_date')) {
                $query->whereDate('transaction_date', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('transaction_date', '<=', $request->end_date);
            }

            $ledgerEntries = $query->orderBy('transaction_date', 'asc')->orderBy('id', 'asc')->get();
            $openingBalance = $selectedAccount->opening_balance;
        }

        return view('admin.erp.accounts.general_ledger', compact(
            'accounts',
            'selectedAccount',
            'ledgerEntries',
            'openingBalance'
        ));
    }

    // ==========================================
    // 3. CASH BOOK & BANK BOOK
    // ==========================================
    public function cashBankIndex(Request $request)
    {
        $bankAccounts = ErpCashBankAccount::all();
        $recentVouchers = ErpVoucher::with('bankAccount')->whereIn('voucher_type', ['payment', 'receipt', 'contra'])->latest()->paginate(15);

        $totalCash = ErpCashBankAccount::where('account_type', 'cash')->sum('current_balance');
        $totalBank = ErpCashBankAccount::where('account_type', 'bank')->sum('current_balance');
        $totalMfs = ErpCashBankAccount::where('account_type', 'mfs')->sum('current_balance');
        $grandTotalFunds = $totalCash + $totalBank + $totalMfs;

        return view('admin.erp.accounts.cash_bank', compact(
            'bankAccounts',
            'recentVouchers',
            'totalCash',
            'totalBank',
            'totalMfs',
            'grandTotalFunds'
        ));
    }

    public function storeCashBankAccount(Request $request)
    {
        $request->validate([
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|in:cash,bank,mfs',
            'opening_balance' => 'nullable|numeric|min:0',
        ]);

        ErpCashBankAccount::create([
            'account_type' => $request->account_type,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'bank_name' => $request->bank_name,
            'branch_name' => $request->branch_name,
            'routing_number' => $request->routing_number,
            'opening_balance' => $request->opening_balance ?? 0,
            'current_balance' => $request->opening_balance ?? 0,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'ক্যাশ/ব্যাংক অ্যাকাউন্ট সফলভাবে যুক্ত হয়েছে।');
    }

    // ==========================================
    // 4. FINANCIAL STATEMENTS: TRIAL BALANCE, P&L, BALANCE SHEET
    // ==========================================
    public function trialBalance()
    {
        $accounts = ErpChartOfAccount::where('is_active', true)->orderBy('account_code')->get();

        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($accounts as $acc) {
            if ($acc->normal_balance === 'debit') {
                $totalDebit += $acc->current_balance;
            } else {
                $totalCredit += $acc->current_balance;
            }
        }

        return view('admin.erp.accounts.trial_balance', compact('accounts', 'totalDebit', 'totalCredit'));
    }

    public function profitAndLoss()
    {
        $incomes = ErpChartOfAccount::where('root_type', 'income')->where('is_active', true)->get();
        $expenses = ErpChartOfAccount::where('root_type', 'expense')->where('is_active', true)->get();

        $totalIncome = $incomes->sum('current_balance');
        $totalExpenses = $expenses->sum('current_balance');
        $netProfit = $totalIncome - $totalExpenses;

        return view('admin.erp.accounts.profit_and_loss', compact('incomes', 'expenses', 'totalIncome', 'totalExpenses', 'netProfit'));
    }

    public function balanceSheet()
    {
        $assets = ErpChartOfAccount::where('root_type', 'asset')->where('is_active', true)->get();
        $liabilities = ErpChartOfAccount::where('root_type', 'liability')->where('is_active', true)->get();
        $equities = ErpChartOfAccount::where('root_type', 'equity')->where('is_active', true)->get();

        $totalAssets = $assets->sum('current_balance');
        $totalLiabilities = $liabilities->sum('current_balance');
        $totalEquity = $equities->sum('current_balance');

        // Net income from P&L added to Equity
        $totalIncome = ErpChartOfAccount::where('root_type', 'income')->sum('current_balance');
        $totalExpenses = ErpChartOfAccount::where('root_type', 'expense')->sum('current_balance');
        $retainedEarnings = $totalIncome - $totalExpenses;
        $grandTotalEquityLiabilities = $totalLiabilities + $totalEquity + $retainedEarnings;

        return view('admin.erp.accounts.balance_sheet', compact(
            'assets',
            'liabilities',
            'equities',
            'totalAssets',
            'totalLiabilities',
            'totalEquity',
            'retainedEarnings',
            'grandTotalEquityLiabilities'
        ));
    }
}

