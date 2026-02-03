<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ForceSameSiteNone
{
    public function handle(Request $request, Closure $next)
    {
        // Sanctum may downgrade SameSite to Lax for non-stateful requests.
        // Force our desired cookie settings after Sanctum runs.
        Config::set('session.same_site', 'none');
        Config::set('session.secure', true);

        return $next($request);
    }
}
