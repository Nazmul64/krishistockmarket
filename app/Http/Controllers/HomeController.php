<?php

namespace App\Http\Controllers;

use App\Models\BuyStock;
use App\Models\Deposit;
use App\Models\MonthlyBazaarOrder;
use App\Models\SellStock;
use App\Models\User;
use App\Models\UserProfite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard according to authenticated user's role.
     *
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Account status check
        if (!$user->isActive()) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'আপনার একাউন্টটি সাময়িকভাবে নিষ্ক্রিয় বা স্থগিত করা হয়েছে।');
        }

        // 2. Strict Role-based Dashboard Redirection (No matching dashboards between roles)
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('manager')) {
            return redirect()->route('manager.dashboard');
        }

        if ($user->hasRole('employee')) {
            return redirect()->route('employee.dashboard');
        }

        if ($user->hasRole('staff')) {
            return redirect()->route('staff.dashboard');
        }

        if ($user->hasRole('support_agent') || $user->hasRole('support_lead')) {
            return redirect()->route('support.dashboard');
        }

        if ($user->hasRole('security_analyst')) {
            return redirect()->route('security.dashboard');
        }

        if ($user->hasRole('auditor')) {
            return redirect()->route('audit.dashboard');
        }

        if ($user->hasRole('supplier') || $user->hasRole('seller')) {
            return redirect()->route('supplier.dashboard');
        }

        // 3. Customer / General User Dashboard
        $all_sell = SellStock::where('user_id', $user->id)->where('status', 'aproved')->sum('selled_price');
        $all_buy = BuyStock::where('user_id', $user->id)->where('status', 'aproved')->sum('buyed_price');
        $user_profit = UserProfite::where('user_id', $user->id)->sum('profit');

        $active_stocks_count = BuyStock::where('user_id', $user->id)->where('status', 'aproved')->count();
        $bazaar_orders_count = MonthlyBazaarOrder::where('user_id', $user->id)->count();

        $recent_bazaar_orders = MonthlyBazaarOrder::where('user_id', $user->id)->with('item')->latest()->take(5)->get();
        $recent_buystocks = BuyStock::where('user_id', $user->id)->with('stock')->latest()->take(5)->get();
        $recent_deposits = Deposit::where('user_id', $user->id)->latest()->take(5)->get();

        return view('users.user-home', [
            'all_sell'             => $all_sell,
            'all_buy'              => $all_buy,
            'user_profit'          => $user_profit,
            'active_stocks_count'  => $active_stocks_count,
            'bazaar_orders_count'  => $bazaar_orders_count,
            'recent_bazaar_orders' => $recent_bazaar_orders,
            'recent_buystocks'     => $recent_buystocks,
            'recent_deposits'      => $recent_deposits,
        ]);
    }
}
