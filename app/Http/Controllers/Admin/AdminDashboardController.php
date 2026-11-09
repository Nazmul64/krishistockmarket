<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\BuyStock;
use App\Models\Deposit;
use App\Models\MonthlyBazaarOrder;
use App\Models\Property;
use App\Models\PropertyInquiry;
use App\Models\Role;
use App\Models\SecurityAlert;
use App\Models\SellStock;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\UserProfite;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('portal:/admin');
    }

    public function index()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $inactiveUsers = User::where('status', '!=', 'active')->count();
        $rolesCount = Role::count();

        // 1. Monthly Bazaar / মাসিক বাজার হিসাব
        $bazaarTotalOrders = MonthlyBazaarOrder::count();
        $bazaarTotalSales = (float) MonthlyBazaarOrder::sum('total_price');
        $bazaarApprovedSales = (float) MonthlyBazaarOrder::whereIn('status', ['approved', 'completed', 'delivered'])->sum('total_price');
        $bazaarPendingOrders = MonthlyBazaarOrder::where('status', 'pending')->count();
        $bazaarCompletedOrders = MonthlyBazaarOrder::whereIn('status', ['completed', 'delivered'])->count();

        // 2. Real Estate & Land Management / জমি ও প্রপার্টি হিসাব
        $propertyTotalCount = Property::count();
        $propertyAvailableCount = Property::where('status', 'available')->count();
        $propertyBookedCount = Property::whereIn('status', ['reserved', 'booked', 'sold'])->count();
        $propertyTotalValuation = (float) Property::where('status', '!=', 'draft')->sum('selling_price');
        $propertyBookingVolume = (float) Property::whereIn('status', ['reserved', 'booked', 'sold'])->sum('booking_amount');
        $propertyTotalInquiries = PropertyInquiry::count();
        $propertyPendingInquiries = PropertyInquiry::where('status', 'pending')->count();
        $propertyConfirmedInquiries = PropertyInquiry::whereIn('status', ['confirmed', 'scheduled'])->count();

        // 3. Stock Market Trading / স্টক মার্কেট হিসাব
        $totalStockBuy = (float) BuyStock::where('status', 'aproved')->sum('buyed_price');
        $totalStockSell = (float) SellStock::where('status', 'aproved')->sum('selled_price');
        $pendingStockBuy = BuyStock::where('status', 'pending')->count();
        $pendingStockSell = SellStock::where('status', 'pending')->count();
        $totalProfitDistributed = (float) UserProfite::sum('profit');

        // 4. Deposits & Withdrawals / প্ল্যাটফর্ম ওয়ালেট ক্যাশ-ফ্লো
        $totalDepositsApproved = (float) Deposit::whereIn('status', ['approved', 'aproved'])->sum('deposit_amount');
        $totalDepositsCount = Deposit::count();
        $pendingDepositsCount = Deposit::where('status', 'pending')->count();
        $pendingDepositsAmount = (float) Deposit::where('status', 'pending')->sum('deposit_amount');

        $totalWithdrawApproved = (float) Withdraw::whereIn('status', ['approved', 'aproved'])->sum('amount');
        $totalWithdrawCount = Withdraw::count();
        $pendingWithdrawsCount = Withdraw::where('status', 'pending')->count();
        $pendingWithdrawsAmount = (float) Withdraw::where('status', 'pending')->sum('amount');

        // Net Platform Vault Reserve (অনুমোদিত জমা - অনুমোদিত উত্তোলন)
        $netVaultBalance = $totalDepositsApproved - $totalWithdrawApproved;

        // Grand Platform Business Turnover (সর্বমোট প্ল্যাটফর্ম লেনদেন ও টার্নওভার)
        $grandTotalTurnover = $bazaarTotalSales + $totalStockBuy + $propertyBookingVolume + $totalDepositsApproved;

        // Recent Financial Activities for Quick Audit
        $recentBazaarOrders = MonthlyBazaarOrder::with('user')->orderBy('id', 'desc')->take(5)->get();
        $recentPropertyInquiries = PropertyInquiry::with('property')->orderBy('id', 'desc')->take(5)->get();
        $recentDeposits = Deposit::with('user')->orderBy('id', 'desc')->take(5)->get();
        $recentWithdraws = Withdraw::with('user')->orderBy('id', 'desc')->take(5)->get();

        // Security & Support summary
        $openTicketsCount = SupportTicket::whereIn('status', ['open', 'in_progress'])->count();
        $activeAlertsCount = SecurityAlert::where('status', 'active')->count();

        // Role distribution
        $roles = Role::withCount('users')->get();

        // Recent Privileged Audit Logs
        $recentAuditLogs = AuditLog::orderBy('id', 'desc')->take(8)->get();

        // Recent Users
        $recentUsers = User::with(['roleRelation', 'userType'])->orderBy('id', 'desc')->take(6)->get();

        return view('admin.admin-dashboard', compact(
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'rolesCount',
            'bazaarTotalOrders',
            'bazaarTotalSales',
            'bazaarApprovedSales',
            'bazaarPendingOrders',
            'bazaarCompletedOrders',
            'propertyTotalCount',
            'propertyAvailableCount',
            'propertyBookedCount',
            'propertyTotalValuation',
            'propertyBookingVolume',
            'propertyTotalInquiries',
            'propertyPendingInquiries',
            'propertyConfirmedInquiries',
            'totalStockBuy',
            'totalStockSell',
            'pendingStockBuy',
            'pendingStockSell',
            'totalProfitDistributed',
            'totalDepositsApproved',
            'totalDepositsCount',
            'pendingDepositsCount',
            'pendingDepositsAmount',
            'totalWithdrawApproved',
            'totalWithdrawCount',
            'pendingWithdrawsCount',
            'pendingWithdrawsAmount',
            'netVaultBalance',
            'grandTotalTurnover',
            'recentBazaarOrders',
            'recentPropertyInquiries',
            'recentDeposits',
            'recentWithdraws',
            'openTicketsCount',
            'activeAlertsCount',
            'roles',
            'recentAuditLogs',
            'recentUsers'
        ));
    }
}
