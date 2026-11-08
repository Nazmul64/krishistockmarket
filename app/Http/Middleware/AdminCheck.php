<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use App\Models\SecurityAlert;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminCheck
{
    /**
     * Handle an incoming request for Admin portal isolation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'অনুগ্রহ করে অ্যাডমিন পোর্টালে লগইন করুন।');
        }

        $user = Auth::user();

        if (!$user->isActive()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'আপনার একাউন্টটি সাময়িকভাবে নিষ্ক্রিয় বা স্থগিত করা হয়েছে।');
        }

        // Only super_admin or admin roles can access /admin routes
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return $next($request);
        }

        // Zero-Trust Breach: Non-admin trying to access Admin domain
        SecurityAlert::create([
            'title'     => 'Cross-Portal Access Violation',
            'severity'  => 'high',
            'source_ip' => $request->ip(),
            'target'    => $request->path(),
            'status'    => 'active',
            'details'   => "Zero Trust Breach: User '{$user->username}' (Role: {$user->role}) attempted unauthorized access to Admin Panel '{$request->path()}' from IP {$request->ip()}",
        ]);

        AuditLog::log(
            'PORTAL_ACCESS_DENIED',
            $user,
            null,
            ['attempted_path' => $request->path(), 'user_role' => $user->role],
            "Cross-portal access denied: {$user->username} attempted Admin Panel access"
        );

        if ($request->expectsJson() || $request->isXmlHttpRequest()) {
            return response()->json([
                'status' => 'forbidden',
                'error'  => 'Unauthorized Portal Access: আপনি এই পোর্টালে প্রবেশের অনুমতিপ্রাপ্ত নন।'
            ], 403);
        }

        return response()->view('errors.portal_forbidden', [
            'user'           => $user,
            'requiredPortal' => '/admin',
            'userPortal'     => $user->getPortalPrefix(),
            'ownDashboard'   => $user->getDashboardRoute(),
            'errorMessage'   => 'Unauthorized Portal Access: আপনি এই পোর্টালে প্রবেশের অনুমতিপ্রাপ্ত নন।'
        ], 403);
    }
}
