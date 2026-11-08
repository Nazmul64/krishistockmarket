<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use App\Models\SecurityAlert;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalGuard
{
    /**
     * Handle an incoming request for portal domain/path isolation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $requiredPortalPrefix
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $requiredPortalPrefix)
    {
        $normalizedRequired = '/' . trim($requiredPortalPrefix, '/');

        // 1. Authentication check
        if (!Auth::check()) {
            $loginRoute = match(trim($requiredPortalPrefix, '/')) {
                'admin'    => 'admin.login',
                'manager'  => 'manager.login',
                'employee' => 'employee.login',
                'staff'    => 'staff.login',
                'support'  => 'support.login',
                'security' => 'security.login',
                'audit'    => 'audit.login',
                default    => 'login',
            };

            if ($request->expectsJson() || $request->isXmlHttpRequest()) {
                return response()->json([
                    'status'  => 'unauthenticated',
                    'message' => 'অনুগ্রহ করে প্রথমে আপনার পোর্টালে লগইন করুন।',
                    'redirect'=> route($loginRoute),
                ], 401);
            }

            return redirect()->route($loginRoute)->with('error', 'অনুগ্রহ করে প্রথমে লগইন করুন।');
        }

        $user = Auth::user();

        // 2. Active status check
        if (!$user->isActive()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'আপনার একাউন্টটি সাময়িকভাবে নিষ্ক্রিয় বা স্থগিত করা হয়েছে।');
        }

        // 3. Portal Prefix & Role Isolation Check
        $userPortal = '/' . trim($user->getPortalPrefix(), '/');

        $isAuthorized = false;

        // Exact match of portal prefix
        if ($userPortal === $normalizedRequired) {
            $isAuthorized = true;
        } elseif ($user->hasRole('super_admin')) {
            // Super Admin has system-wide oversight
            $isAuthorized = true;
        }

        if (!$isAuthorized) {
            // Log security incident to Cyber Alerts & Audit Trail in real time
            SecurityAlert::create([
                'title'     => 'Cross-Portal Access Violation',
                'severity'  => 'high',
                'source_ip' => $request->ip(),
                'target'    => $request->path(),
                'status'    => 'active',
                'details'   => "Zero Trust Breach: User '{$user->username}' (Role: {$user->role}, Portal: {$userPortal}) attempted unauthorized access to '{$request->path()}' (Required Portal: {$normalizedRequired}) from IP {$request->ip()}",
            ]);

            AuditLog::log(
                'PORTAL_ACCESS_DENIED',
                $user,
                null,
                [
                    'attempted_path'   => $request->path(),
                    'required_portal'  => $normalizedRequired,
                    'user_portal'      => $userPortal,
                    'user_role'        => $user->role,
                ],
                "Cross-portal access denied: User {$user->username} tried entering {$normalizedRequired}"
            );

            if ($request->expectsJson() || $request->isXmlHttpRequest()) {
                return response()->json([
                    'status' => 'forbidden',
                    'error'  => 'Unauthorized Portal Access: আপনি এই পোর্টালে প্রবেশের অনুমতিপ্রাপ্ত নন।'
                ], 403);
            }

            return response()->view('errors.portal_forbidden', [
                'user'            => $user,
                'requiredPortal'  => $normalizedRequired,
                'userPortal'      => $userPortal,
                'ownDashboard'    => $user->getDashboardRoute(),
                'errorMessage'    => 'Unauthorized Portal Access: আপনি এই পোর্টালে প্রবেশের অনুমতিপ্রাপ্ত নন।'
            ], 403);
        }

        return $next($request);
    }
}

