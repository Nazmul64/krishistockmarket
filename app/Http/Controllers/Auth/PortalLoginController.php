<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Blocklist;
use App\Models\SecurityAlert;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class PortalLoginController extends Controller
{
    /**
     * Show isolated login form for a specific portal.
     */
    public function showLoginForm(Request $request, string $portal = 'customer')
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->getDashboardRoute());
        }

        $config = $this->getPortalConfig($portal);

        return view('auth.portal_login', compact('portal', 'config'));
    }

    /**
     * Handle authentication for a specific portal with Zero-Trust isolation.
     */
    public function login(Request $request, string $portal = 'customer')
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $ip = $request->ip();

        // 1. IP Blocklist Check
        $isBlocked = Blocklist::where('status', 'active')
            ->where(function ($q) use ($ip, $request) {
                $q->where('value', $ip)
                  ->orWhere('value', $request->input('username'));
            })->exists();

        if ($isBlocked) {
            SecurityAlert::create([
                'title'     => 'Blocked Entity Login Attempt',
                'severity'  => 'critical',
                'source_ip' => $ip,
                'target'    => "{$portal}/login",
                'status'    => 'active',
                'details'   => "Blocked IP/Username attempted login on {$portal} portal. Input: {$request->input('username')}",
            ]);

            return back()->withInput($request->only('username'))->with('error', 'আপনার আইপি বা অ্যাকাউন্টটি নিরাপত্তা নীতি ভঙ্গের কারণে ব্লকলিস্টে রয়েছে।');
        }

        // 2. Rate Limiting / Brute-force protection
        $throttleKey = Str::transliterate(Str::lower($request->input('username')) . '|' . $ip);
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            SecurityAlert::create([
                'title'     => 'Brute-force Login Attack Detected',
                'severity'  => 'high',
                'source_ip' => $ip,
                'target'    => "{$portal}/login",
                'status'    => 'active',
                'details'   => "Exceeded maximum login attempts (5) on {$portal} login from IP {$ip}. Locked for {$seconds} seconds.",
            ]);

            return back()->withInput($request->only('username'))->with('error', "অতিরিক্ত ব্যর্থ লগইন চেষ্টা! অনুগ্রহ করে {$seconds} সেকেন্ড পর পুনরায় চেষ্টা করুন।");
        }

        // 3. Find User by username, email or phone
        $input = trim($request->input('username'));
        $user = User::where('username', $input)
            ->orWhere('email', $input)
            ->orWhere('phone', $input)
            ->first();

        // 4. Verify password
        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            RateLimiter::hit($throttleKey, 300);

            AuditLog::log(
                'FAILED_LOGIN_CREDENTIALS',
                $user,
                null,
                ['attempted_user' => $input, 'portal' => $portal],
                "Failed login attempt on {$portal} portal from IP {$ip}"
            );

            return back()->withInput($request->only('username'))->with('error', 'প্রদত্ত তথ্য বা পাসওয়ার্ড সঠিক নয়। অনুগ্রহ করে পুনরায় চেষ্টা করুন।');
        }

        // 5. Account status check
        if (!$user->isActive()) {
            AuditLog::log(
                'LOGIN_BLOCKED_INACTIVE_USER',
                $user,
                null,
                ['portal' => $portal],
                "Inactive user {$user->username} attempted login"
            );

            return back()->withInput($request->only('username'))->with('error', 'আপনার অ্যাকাউন্টটি সাময়িকভাবে নিষ্ক্রিয় বা স্থগিত করা হয়েছে। অ্যাডমিনের সাথে যোগাযোগ করুন।');
        }

        // 6. Zero-Trust Portal Isolation Enforcement
        $authorizedRoles = $this->getAuthorizedRolesForPortal($portal);

        $hasAccess = false;
        foreach ($authorizedRoles as $role) {
            if ($user->hasRole($role)) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            RateLimiter::hit($throttleKey, 300);

            // Log security alert for cross-portal intrusion
            SecurityAlert::create([
                'title'     => 'Cross-Portal Login Violation Attempt',
                'severity'  => 'high',
                'source_ip' => $ip,
                'target'    => "{$portal}/login",
                'status'    => 'active',
                'details'   => "Zero Trust Breach: User '{$user->username}' with role '{$user->role}' attempted unauthorized login to '{$portal}' portal from IP {$ip}.",
            ]);

            AuditLog::log(
                'UNAUTHORIZED_PORTAL_LOGIN_ATTEMPT',
                $user,
                null,
                [
                    'attempted_portal' => $portal,
                    'user_role'        => $user->role,
                    'user_portal'      => $user->getPortalPrefix(),
                ],
                "Cross-portal login blocked: User {$user->username} attempted to enter {$portal} portal"
            );

            return back()->withInput($request->only('username'))->with('error', 'Unauthorized Portal Access: আপনি এই পোর্টালে প্রবেশের অনুমতিপ্রাপ্ত নন! অনুগ্রহ করে আপনার নির্ধারিত পোর্টালে লগইন করুন।');
        }

        // Clear rate limiter
        RateLimiter::clear($throttleKey);

        // 7. Login to guards
        $guardName = $this->getGuardNameForPortal($portal);
        if (config("auth.guards.{$guardName}")) {
            Auth::guard($guardName)->login($user, $request->boolean('remember'));
        }
        Auth::guard('web')->login($user, $request->boolean('remember'));

        // Update login stats
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
        ]);

        $request->session()->regenerate();
        $request->session()->put('active_portal', $portal);

        AuditLog::log(
            'PORTAL_LOGIN_SUCCESS',
            $user,
            null,
            ['portal' => $portal, 'guard' => $guardName],
            "User {$user->username} successfully logged into {$portal} portal"
        );

        // Redirect to isolated dashboard
        return redirect()->intended(route($user->getDashboardRoute()));
    }

    /**
     * Handle portal logout.
     */
    public function logout(Request $request, string $portal = 'customer')
    {
        $user = Auth::user();

        if ($user) {
            AuditLog::log(
                'USER_LOGOUT',
                $user,
                null,
                ['portal' => $portal],
                "User {$user->username} logged out from {$portal} portal"
            );
        }

        $guardName = $this->getGuardNameForPortal($portal);
        if (config("auth.guards.{$guardName}")) {
            Auth::guard($guardName)->logout();
        }
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $loginRoute = match($portal) {
            'admin'    => 'admin.login',
            'manager'  => 'manager.login',
            'employee' => 'employee.login',
            'staff'    => 'staff.login',
            'support'  => 'support.login',
            'security' => 'security.login',
            'audit'    => 'audit.login',
            default    => 'login',
        };

        return redirect()->route($loginRoute)->with('success', 'আপনি সফলভাবে লগআউট হয়েছেন।');
    }

    /**
     * Map portal key to authorized roles.
     */
    private function getAuthorizedRolesForPortal(string $portal): array
    {
        return match($portal) {
            'admin'    => ['super_admin', 'admin'],
            'manager'  => ['manager', 'super_admin'],
            'employee' => ['employee'],
            'staff'    => ['staff'],
            'support'  => ['support_lead', 'support_agent', 'super_admin'],
            'security' => ['security_analyst', 'super_admin'],
            'audit'    => ['auditor', 'super_admin'],
            default    => ['user', 'customer'],
        };
    }

    /**
     * Map portal key to auth guard name.
     */
    private function getGuardNameForPortal(string $portal): string
    {
        return match($portal) {
            'admin'    => 'admin_guard',
            'manager'  => 'staff_guard',
            'employee' => 'staff_guard',
            'staff'    => 'staff_guard',
            'support'  => 'support_guard',
            'security' => 'security_guard',
            'audit'    => 'audit_guard',
            default    => 'customer_guard',
        };
    }

    /**
     * Configuration and metadata for each isolated portal.
     */
    private function getPortalConfig(string $portal): array
    {
        return match($portal) {
            'admin' => [
                'title'       => 'Admin Command Center',
                'subtitle'    => 'সিস্টেম কনফিগারেশন, রোল অ্যাসাইনমেন্ট ও ইউজার লাইফসাইকেল',
                'badge'       => 'admin_guard | Zero Trust Protected',
                'bg_gradient' => 'linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #431407 100%)',
                'header_grad' => 'linear-gradient(135deg, #ea580c 0%, #d97706 50%, #f59e0b 100%)',
                'action_url'  => route('admin.login.post'),
                'icon'        => 'ti-crown',
            ],
            'manager' => [
                'title'       => 'Manager Operations Portal',
                'subtitle'    => 'টিম তদারকি, অ্যাপ্রুভাল প্রসেস ও বিভাগীয় রিপোর্ট',
                'badge'       => 'staff_guard | Manager Context',
                'bg_gradient' => 'linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #064e3b 100%)',
                'header_grad' => 'linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%)',
                'action_url'  => route('manager.login.post'),
                'icon'        => 'ti-briefcase',
            ],
            'employee' => [
                'title'       => 'Employee Task Portal',
                'subtitle'    => 'অ্যাসাইন করা নির্দিষ্ট টাস্ক ও প্যাকেজ কনটেন্ট ব্যবস্থাপনা',
                'badge'       => 'staff_guard | Employee Context',
                'bg_gradient' => 'linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #1e3a8a 100%)',
                'header_grad' => 'linear-gradient(135deg, #2563eb 0%, #3b82f6 50%, #60a5fa 100%)',
                'action_url'  => route('employee.login.post'),
                'icon'        => 'ti-id-badge',
            ],
            'staff' => [
                'title'       => 'Operations Staff Portal',
                'subtitle'    => 'দৈনিক অপারেশনাল ইনপুট ও সীমিত ডেটা এন্ট্রি',
                'badge'       => 'staff_guard | Operations',
                'bg_gradient' => 'linear-gradient(135deg, #0f172a 0%, #334155 50%, #1c1917 100%)',
                'header_grad' => 'linear-gradient(135deg, #475569 0%, #64748b 50%, #94a3b8 100%)',
                'action_url'  => route('staff.login.post'),
                'icon'        => 'ti-clipboard',
            ],
            'support' => [
                'title'       => 'Customer Support Hub',
                'subtitle'    => 'লাইভ চ্যাট হ্যান্ডলিং, সরাসরি গ্রাহকের টিকেট রিপ্লাই ও এস্কেলেশন',
                'badge'       => 'support_guard | Live Desk',
                'bg_gradient' => 'linear-gradient(135deg, #0f172a 0%, #2e1065 50%, #581c87 100%)',
                'header_grad' => 'linear-gradient(135deg, #7c3aed 0%, #8b5cf6 50%, #a78bfa 100%)',
                'action_url'  => route('support.login.post'),
                'icon'        => 'ti-headphone-alt',
            ],
            'security' => [
                'title'       => 'Cyber Security SOC Portal',
                'subtitle'    => 'লাইভ সাইবার অ্যালার্ট, ব্রুট-ফোর্স মনিটরিং ও আইপি ব্লকলিস্ট',
                'badge'       => 'security_guard | SOC Hub',
                'bg_gradient' => 'linear-gradient(135deg, #050505 0%, #450a0a 50%, #1c1917 100%)',
                'header_grad' => 'linear-gradient(135deg, #dc2626 0%, #ef4444 50%, #f87171 100%)',
                'action_url'  => route('security.login.post'),
                'icon'        => 'ti-shield',
            ],
            'audit' => [
                'title'       => 'Compliance & Audit Portal',
                'subtitle'    => 'সিস্টেম অডিট ট্রেইল, অপরিবর্তনীয় লগ ও কমপ্লায়েন্স রিপোর্ট',
                'badge'       => 'audit_guard | Read-Only Inspection',
                'bg_gradient' => 'linear-gradient(135deg, #09090b 0%, #18181b 50%, #27272a 100%)',
                'header_grad' => 'linear-gradient(135deg, #0284c7 0%, #0ea5e9 50%, #38bdf8 100%)',
                'action_url'  => route('audit.login.post'),
                'icon'        => 'ti-agenda',
            ],
            default => [
                'title'       => 'Customer Member Portal',
                'subtitle'    => 'নিজস্ব সার্ভিস, প্রোফাইল ম্যানেজমেন্ট ও সাপোর্ট টিকেট তৈরি',
                'badge'       => 'customer_guard | Member Services',
                'bg_gradient' => 'linear-gradient(135deg, #0b1120 0%, #1c1917 50%, #431407 100%)',
                'header_grad' => 'linear-gradient(135deg, #ea580c 0%, #d97706 50%, #f59e0b 100%)',
                'action_url'  => route('login.post'),
                'icon'        => 'ti-user',
            ],
        };
    }
}

