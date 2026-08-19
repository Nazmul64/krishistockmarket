<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->role === 'admin') {
                return $next($request);
            }

            if ($user->role === 'employee') {
                $routeName = optional($request->route())->getName() ?: '';
                $permissionKey = $this->getPermissionKeyForRoute($routeName, $request->path());

                if ($permissionKey) {
                    if ($user->hasPermission($permissionKey)) {
                        return $next($request);
                    }
                    return redirect()->route('home')->with('error', 'আপনার এই পেজে প্রবেশের অনুমতি নেই।');
                }

                return $next($request);
            }

            return redirect()->route('home');
        }

        return redirect()->route('login');
    }

    /**
     * Map route names or URL paths to permission keys.
     */
    private function getPermissionKeyForRoute($routeName, $path)
    {
        if (str_contains($routeName, 'admin.chat')) return 'live_chat';
        if (str_contains($routeName, 'admin.contact_messages')) return 'contact_messages';
        if (str_contains($routeName, 'admin.our_packages')) return 'our_packages';
        if (str_contains($routeName, 'admin.card_numbers')) return 'card_numbers';

        if (str_contains($routeName, 'admin.stock_preset') || $routeName === 'admin.stock.index' || $routeName === 'admin.stock.allStock' || $routeName === 'admin.stock.post') return 'stock_management';
        if ($routeName === 'admin.stock.list' || str_contains($routeName, 'admin.stock.pricing') || str_contains($routeName, 'admin.stock.add.price')) return 'set_stock_price';
        if (str_contains($routeName, 'admin.stock.buyrequest') || str_contains($routeName, 'admin.stock.sellrequest') || str_contains($routeName, 'admin.buy.stock') || str_contains($routeName, 'admin.sell.stock')) return 'stock_buy_sell';

        if (str_contains($routeName, 'admin.monthly_bazaar')) return 'monthly_bazaar';
        if ($routeName === 'alluser' || str_contains($routeName, 'admin.deposit') || str_contains($routeName, 'admin.reports') || str_contains($routeName, 'admin.user.')) return 'user_financial';
        if (str_contains($routeName, 'admin.all.withdraw')) return 'withdraw';
        if (str_contains($routeName, 'admin.employee') || str_contains($routeName, 'admin.agent_ledger')) return 'employee_agent';
        if (str_contains($routeName, 'admin.suppliers')) return 'supplier_management';
        if (str_contains($routeName, 'admin.hrm')) return 'hrm_management';
        if (str_contains($routeName, 'setting.') || str_contains($routeName, 'admin.feature') || str_contains($routeName, 'admin.payment')) return 'settings';

        return null;
    }
}
