<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request for granular permission enforcement.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $permission)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->isActive()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'আপনার একাউন্টটি সাময়িকভাবে নিষ্ক্রিয় বা স্থগিত করা হয়েছে।');
        }

        // Super Admin has universal access
        if ($user->hasRole('super_admin') || $user->hasPermission($permission)) {
            return $next($request);
        }

        // Log permission denial in audit trail
        AuditLog::log(
            'ACTION_FORBIDDEN',
            $user,
            null,
            [
                'permission_slug' => $permission,
                'path'            => $request->path(),
                'method'          => $request->method(),
            ],
            "Action Forbidden: User {$user->username} attempted action requiring permission '{$permission}'"
        );

        $errorMessage = "Action Forbidden: '{$permission}' পারমিশন নেই।";

        if ($request->expectsJson() || $request->isXmlHttpRequest()) {
            return response()->json([
                'status' => 'forbidden',
                'error'  => $errorMessage,
            ], 403);
        }

        return redirect()->route($user->getDashboardRoute())->with('error', $errorMessage);
    }
}
