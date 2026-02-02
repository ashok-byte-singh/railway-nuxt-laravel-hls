<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class CookieServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Config::set('session.same_site', 'none');
        Config::set('session.secure', true);
    }
}
