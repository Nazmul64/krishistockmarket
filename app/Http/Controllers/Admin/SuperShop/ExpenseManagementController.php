<?php

namespace App\Http\Controllers\Admin\SuperShop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuperShop\Branch;
use App\Models\SuperShop\BranchExpense;
use App\Models\SuperShop\ExpenseCategory;
use Illuminate\Support\Facades\Auth;

class ExpenseManagementController extends Controller
{
    /**
     * Branch Expenses Listing.
     */
    public function index(Request $request)
    {
        $branches = Branch::where('status', 'active')->get();
        $categories = ExpenseCategory::where('is_active', true)->get();

        $selectedBranchId = $request->get('branch_id');
        $selectedCatId = $request->get('category_id');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        $query = BranchExpense::with(['branch', 'category', 'creator'])->orderBy('expense_date', 'desc');

        if ($selectedBranchId) {
            $query->where('branch_id', $selectedBranchId);
        }
        if ($selectedCatId) {
            $query->where('category_id', $selectedCatId);
        }
        if ($fromDate) {
            $query->whereDate('expense_date', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('expense_date', '<=', $toDate);
        }

        $expenses = $query->paginate(20)->withQueryString();
        $totalExpenseAmount = (float)$query->sum('amount');

        return view('admin.supershop.expenses.index', compact('expenses', 'branches', 'categories', 'selectedBranchId', 'selectedCatId', 'totalExpenseAmount'));
    }

    /**
     * Store Branch Expense.
     */
    public function store(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'category_id' => 'required|exists:expense_categories,id',
            'title' => 'required|string|max:200',
            'amount' => 'required|numeric|min:1',
            'expense_date' => 'required|date',
        ]);

        BranchExpense::create([
            'branch_id' => $request->branch_id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'amount' => (float)$request->amount,
            'expense_date' => $request->expense_date,
            'payment_method' => $request->payment_method ?: 'Cash',
            'description' => $request->description,
            'status' => 'paid',
            'created_by' => Auth::id(),
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'শাখার খরচ সফলভাবে রেকর্ড করা হয়েছে!');
    }

    /**
     * Delete expense.
     */
    public function destroy($id)
    {
        $exp = BranchExpense::findOrFail($id);
        $exp->delete();
        return back()->with('success', 'খরচ রেকর্ডটি মুছে ফেলা হয়েছে!');
    }
}

