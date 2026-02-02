<?php

namespace App\Providers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class SessionCookieServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Session::configure([
            'same_site' => 'none',
            'secure' => true,
        ]);
    }
}
