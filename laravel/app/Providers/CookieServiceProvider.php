<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Cookie as SymfonyCookie;

class CookieServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Cookie::macro('session', function (
            string $name,
            string $value,
            int $minutes = 120,
            string $path = '/',
            string $domain = null,
            bool $secure = true,
            bool $httpOnly = true
        ) {
            return new SymfonyCookie(
                $name,
                $value,
                time() + ($minutes * 60),
                $path,
                $domain,
                $secure,
                $httpOnly,
                false,
                'none' // 👈 FORCE SameSite=None
            );
        });
    }
}
