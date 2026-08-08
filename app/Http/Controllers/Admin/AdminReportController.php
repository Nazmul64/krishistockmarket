<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentStockTransaction;
use App\Models\BuyStock;
use App\Models\Deposit;
use App\Models\MonthlyBazaarOrder;
use App\Models\User;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = null;
        $endDate = null;

        if ($request->filter == 'today') {
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
        } elseif ($request->filter == 'weekly') {
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
        } elseif ($request->filter == 'monthly') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        } elseif ($request->start_date && $request->end_date) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
        }

        // Apply Date Filters
        $usersQuery = User::where('role', 'user');
        $depositsQuery = Deposit::where('status', 'approved');
        $stockPurchasesQuery = BuyStock::where('status', 'aproved');
        $monthlyBazaarQuery = MonthlyBazaarOrder::where('status', 'approved');
        $agentSalesQuery = AgentStockTransaction::where('transaction_type', 'sale');
        $walletTxnQuery = WalletTransaction::query();

        if ($startDate && $endDate) {
            $usersQuery->whereBetween('created_at', [$startDate, $endDate]);
            $depositsQuery->whereBetween('updated_at', [$startDate, $endDate]);
            $stockPurchasesQuery->whereBetween('updated_at', [$startDate, $endDate]);
            $monthlyBazaarQuery->whereBetween('updated_at', [$startDate, $endDate]);
            $agentSalesQuery->whereBetween('created_at', [$startDate, $endDate]);
            $walletTxnQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalCustomers = User::where('role', 'user')->count();
        $newCustomers = $usersQuery->count();
        $totalCustomerBalance = User::where('role', 'user')->sum('balance');
        $totalApprovedDeposits = $depositsQuery->sum('deposit_amount');
        $totalStockPurchases = $stockPurchasesQuery->sum('buyed_price');
        $totalMonthlyBazaarSales = $monthlyBazaarQuery->sum('total_price');
        $totalAgentSales = $agentSalesQuery->sum('total_price');
        $recentTransactions = $walletTxnQuery->latest()->take(50)->get();

        return view('admin.reports.index', compact(
            'totalCustomers',
            'newCustomers',
            'totalCustomerBalance',
            'totalApprovedDeposits',
            'totalStockPurchases',
            'totalMonthlyBazaarSales',
            'totalAgentSales',
            'recentTransactions',
            'startDate',
            'endDate'
        ));
    }
}
