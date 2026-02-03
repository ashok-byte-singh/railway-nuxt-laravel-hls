<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Force session cookie settings from env at runtime.
        Config::set('session.same_site', env('SESSION_SAME_SITE', 'none'));
        Config::set('session.secure', env('SESSION_SECURE_COOKIE', true));
    }
}
