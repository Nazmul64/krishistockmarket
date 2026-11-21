<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentChecker
{
    /**
     * Handle an incoming request for Agent Portal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if (!$user->isActive()) {
                Auth::logout();
                return redirect()->route('agent.login')->with('error', 'আপনার এজেন্ট অ্যাকাউন্টটি নিষ্ক্রিয় করা হয়েছে।');
            }

            if ($user->hasRole('agent') || $user->hasRole('super_admin') || $user->hasRole('admin')) {
                return $next($request);
            }

            return redirect()->route($user->getDashboardRoute())->with('error', 'এজেন্ট প্যানেলে অন্য ব্যবহারকারীর প্রবেশের অনুমতি নেই।');
        }

        return redirect()->route('agent.login');
    }
}
