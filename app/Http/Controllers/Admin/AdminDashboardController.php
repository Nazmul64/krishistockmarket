<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\BuyStock;
use App\Models\Deposit;
use App\Models\Role;
use App\Models\SecurityAlert;
use App\Models\SellStock;
use App\Models\SupportTicket;
use App\Models\User;
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

        // Financial KPIs
        $totalSellApproved = SellStock::where('status', 'aproved')->sum('selled_price');
        $totalBuyApproved = BuyStock::where('status', 'aproved')->sum('buyed_price');
        $pendingWithdraws = Withdraw::where('status', 'pending')->count();
        $pendingDeposits = Deposit::where('status', 'pending')->count();

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
            'totalSellApproved',
            'totalBuyApproved',
            'pendingWithdraws',
            'pendingDeposits',
            'openTicketsCount',
            'activeAlertsCount',
            'roles',
            'recentAuditLogs',
            'recentUsers'
        ));
    }
}
