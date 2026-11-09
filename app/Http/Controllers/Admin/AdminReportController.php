<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentStockTransaction;
use App\Models\BuyStock;
use App\Models\Deposit;
use App\Models\MonthlyBazaarOrder;
use App\Models\Property;
use App\Models\PropertyInquiry;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\Withdraw;
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
        $depositsQuery = Deposit::whereIn('status', ['approved', 'aproved']);
        $withdrawsQuery = Withdraw::whereIn('status', ['approved', 'aproved']);
        $stockPurchasesQuery = BuyStock::where('status', 'aproved');
        $monthlyBazaarQuery = MonthlyBazaarOrder::whereIn('status', ['approved', 'completed', 'delivered']);
        $agentSalesQuery = AgentStockTransaction::where('transaction_type', 'sale');
        $propertiesQuery = Property::where('status', '!=', 'draft');
        $propertyInquiriesQuery = PropertyInquiry::query();
        $walletTxnQuery = WalletTransaction::query();

        if ($startDate && $endDate) {
            $usersQuery->whereBetween('created_at', [$startDate, $endDate]);
            $depositsQuery->whereBetween('updated_at', [$startDate, $endDate]);
            $withdrawsQuery->whereBetween('updated_at', [$startDate, $endDate]);
            $stockPurchasesQuery->whereBetween('updated_at', [$startDate, $endDate]);
            $monthlyBazaarQuery->whereBetween('updated_at', [$startDate, $endDate]);
            $agentSalesQuery->whereBetween('created_at', [$startDate, $endDate]);
            $propertiesQuery->whereBetween('created_at', [$startDate, $endDate]);
            $propertyInquiriesQuery->whereBetween('created_at', [$startDate, $endDate]);
            $walletTxnQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalCustomers = User::where('role', 'user')->count();
        $newCustomers = $usersQuery->count();
        $totalCustomerBalance = (float) User::where('role', 'user')->sum('balance');
        $totalApprovedDeposits = (float) $depositsQuery->sum('deposit_amount');
        $totalApprovedWithdraws = (float) $withdrawsQuery->sum('amount');
        $totalStockPurchases = (float) $stockPurchasesQuery->sum('buyed_price');
        $totalMonthlyBazaarSales = (float) $monthlyBazaarQuery->sum('total_price');
        $totalAgentSales = (float) $agentSalesQuery->sum('total_price');

        // Property calculations
        $totalPropertyValuation = (float) $propertiesQuery->sum('selling_price');
        $totalPropertyBookings = (float) Property::whereIn('status', ['reserved', 'booked', 'sold'])->sum('booking_amount');
        $totalPropertyInquiries = $propertyInquiriesQuery->count();

        // Platform calculations
        $grandTotalTurnover = $totalApprovedDeposits + $totalStockPurchases + $totalMonthlyBazaarSales + $totalPropertyBookings;
        $netVaultReserve = $totalApprovedDeposits - $totalApprovedWithdraws;

        $recentTransactions = $walletTxnQuery->latest()->take(50)->get();

        return view('admin.reports.index', compact(
            'totalCustomers',
            'newCustomers',
            'totalCustomerBalance',
            'totalApprovedDeposits',
            'totalApprovedWithdraws',
            'totalStockPurchases',
            'totalMonthlyBazaarSales',
            'totalAgentSales',
            'totalPropertyValuation',
            'totalPropertyBookings',
            'totalPropertyInquiries',
            'grandTotalTurnover',
            'netVaultReserve',
            'recentTransactions',
            'startDate',
            'endDate'
        ));
    }
}
