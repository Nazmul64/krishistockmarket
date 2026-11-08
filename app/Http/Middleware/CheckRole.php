<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Account status check (deny inactive / suspended users)
        if (!$user->isActive()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'আপনার একাউন্টটি সাময়িকভাবে নিষ্ক্রিয় বা স্থগিত করা হয়েছে।');
        }

        // Super admin has universal access
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        // Check if user has any of the requested roles
        if ($user->hasAnyRole($roles)) {
            return $next($request);
        }

        // If user lacks the role, block cross-dashboard access and redirect to their own role dashboard
        $ownDashboard = $user->getDashboardRoute();

        if ($request->expectsJson()) {
            return response()->json([
                'status'  => 'forbidden',
                'message' => 'অননুমোদিত এক্সেস! আপনার এই রোলে প্রবেশের অনুমতি নেই।',
            ], 403);
        }

        return redirect()->route($ownDashboard)->with('error', 'অননুমোদিত এক্সেস! অন্য রোলের ড্যাশবোর্ড বা পেজে প্রবেশের অনুমতি নেই।');
    }
}
