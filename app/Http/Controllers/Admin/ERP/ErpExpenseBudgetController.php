<?php

namespace App\Http\Controllers\Admin\ERP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ERP\ErpExpenseCategory;
use App\Models\ERP\ErpExpenseEntry;
use App\Models\ERP\ErpIncomeCategory;
use App\Models\ERP\ErpIncomeEntry;
use App\Models\ERP\ErpBudget;
use App\Models\ERP\ErpCashBankAccount;
use App\Models\ERP\ErpVoucher;

class ErpExpenseBudgetController extends Controller
{
    // ==========================================
    // 1. EXPENSES MANAGEMENT
    // ==========================================
    public function expensesIndex(Request $request)
    {
        $query = ErpExpenseEntry::with(['category', 'cashBankAccount']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        $expenses = $query->latest()->paginate(15);
        $categories = ErpExpenseCategory::where('is_active', true)->get();
        $bankAccounts = ErpCashBankAccount::where('is_active', true)->get();

        $totalExpensesCount = ErpExpenseEntry::count();
        $totalExpenseAmount = ErpExpenseEntry::where('approval_status', 'approved')->sum('amount');
        $pendingApprovalAmount = ErpExpenseEntry::where('approval_status', 'pending')->sum('amount');

        return view('admin.erp.expenses_budget.expenses', compact(
            'expenses',
            'categories',
            'bankAccounts',
            'totalExpensesCount',
            'totalExpenseAmount',
            'pendingApprovalAmount'
        ));
    }

    public function storeExpense(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:erp_expense_categories,id',
            'expense_date' => 'required|date',
            'payee' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'cash_bank_account_id' => 'required|exists:erp_cash_bank_accounts,id',
        ]);

        $expNo = 'EXP-' . date('Ymd') . '-' . rand(100, 999);
        $amount = $request->amount;
        $bankAccount = ErpCashBankAccount::findOrFail($request->cash_bank_account_id);

        if ($bankAccount->current_balance < $amount) {
            return redirect()->back()->with('error', "নির্বাচিত একাউন্টে পর্যাপ্ত ব্যালেন্স নেই! বর্তমান ব্যালেন্স: ৳" . number_format($bankAccount->current_balance, 2));
        }

        DB::beginTransaction();
        try {
            $expense = new ErpExpenseEntry();
            $expense->expense_number = $expNo;
            $expense->expense_date = $request->expense_date;
            $expense->category_id = $request->category_id;
            $expense->department_name = $request->department_name;
            $expense->payee = $request->payee;
            $expense->amount = $amount;
            $expense->payment_method = $request->payment_method ?? 'cash';
            $expense->cash_bank_account_id = $bankAccount->id;
            $expense->description = $request->description;
            $expense->approval_status = 'approved';
            $expense->created_by = auth()->id();

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $filename = 'exp_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('upload/erp/expenses'), $filename);
                $expense->attachment = 'upload/erp/expenses/' . $filename;
            }

            $expense->save();

            // Deduct Bank Account Balance
            $bankAccount->decrement('current_balance', $amount);

            // Update matching budget if exists
            $category = ErpExpenseCategory::find($request->category_id);
            if ($category && $request->department_name) {
                $budget = ErpBudget::where('department_name', $request->department_name)
                    ->where('budget_head', 'like', "%{$category->name}%")
                    ->first();
                if ($budget) {
                    $budget->increment('spent_amount', $amount);
                    $budget->remaining_amount = max(0, $budget->budget_amount - $budget->spent_amount);
                    if ($budget->spent_amount > $budget->budget_amount) {
                        $budget->status = 'exceeded';
                    }
                    $budget->save();
                }
            }

            DB::commit();
            return redirect()->back()->with('success', "ব্যয় ভাউচার ({$expNo}) বাবদ ৳" . number_format($amount, 2) . " সফলভাবে রেকর্ড করা হয়েছে।");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'ব্যয় এন্ট্রি ব্যর্থ: ' . $e->getMessage());
        }
    }

    // ==========================================
    // 2. BUDGET MANAGEMENT
    // ==========================================
    public function budgetsIndex()
    {
        $budgets = ErpBudget::latest()->get();
        $totalBudgetAmount = ErpBudget::sum('budget_amount');
        $totalSpentAmount = ErpBudget::sum('spent_amount');
        $totalRemainingAmount = ErpBudget::sum('remaining_amount');

        return view('admin.erp.expenses_budget.budgets', compact(
            'budgets',
            'totalBudgetAmount',
            'totalSpentAmount',
            'totalRemainingAmount'
        ));
    }

    public function storeBudget(Request $request)
    {
        $request->validate([
            'department_name' => 'required|string|max:255',
            'budget_head' => 'required|string|max:255',
            'budget_amount' => 'required|numeric|min:1',
            'fiscal_year' => 'nullable|string',
        ]);

        $amount = $request->budget_amount;

        ErpBudget::create([
            'fiscal_year' => $request->fiscal_year ?? '2026-2027',
            'department_name' => $request->department_name,
            'budget_head' => $request->budget_head,
            'budget_amount' => $amount,
            'spent_amount' => 0,
            'remaining_amount' => $amount,
            'alert_threshold_percentage' => $request->alert_threshold_percentage ?? 90.00,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'বিভাগভিত্তিক বাজেট সফলভাবে নির্ধারণ করা হয়েছে।');
    }
}

