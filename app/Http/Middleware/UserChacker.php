<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserChacker
{
    /**
     * Handle an incoming request.
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
                return redirect()->route('login')->with('error', 'আপনার একাউন্টটি নিষ্ক্রিয় করা হয়েছে।');
            }

            return $next($request);
        }

        return redirect()->route('login');
    }
}
