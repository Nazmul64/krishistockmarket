<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 1. Blade directive for granular permission checking
        Blade::if('canPermission', function ($permission) {
            return Auth::check() && Auth::user()->hasPermission($permission);
        });

        Blade::if('cannotPermission', function ($permission) {
            return !Auth::check() || !Auth::user()->hasPermission($permission);
        });

        // 2. Blade directive for role check
        Blade::if('hasRole', function ($role) {
            return Auth::check() && Auth::user()->hasRole($role);
        });

        // 3. Blade directive for portal prefix check
        Blade::if('portal', function ($prefix) {
            return Auth::check() && Auth::user()->getPortalPrefix() === '/' . trim($prefix, '/');
        });
    }
}
