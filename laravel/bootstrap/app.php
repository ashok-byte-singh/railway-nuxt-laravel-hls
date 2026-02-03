<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Http\Middleware\HandleCors;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        /**
         * 🌍 Global CORS
         */
        $middleware->use([
            TrustProxies::class,
            HandleCors::class,
        ]);

        /**
         * 🔐 Sanctum SPA middleware (REQUIRED)
         */
        $middleware->api([
            EnsureFrontendRequestsAreStateful::class,
            \App\Http\Middleware\ForceSameSiteNone::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);
        /**
         * 🍪 Default cookie encryption (DO NOT REMOVE)
         */
        $middleware->encryptCookies();
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        /**
         * Return JSON for API auth errors
         */
        $exceptions->render(function (
            AuthenticationException $e,
            Request $request
        ) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Unauthenticated',
                ], 401);
            }
        });
    })
    ->create();
