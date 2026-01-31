<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Middleware\HandleCors;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        /**
         * 🔥 GLOBAL CORS (must run early)
         */
        $middleware->use([
            HandleCors::class,
        ]);

        /**
         * ✅ CSRF exceptions for API auth
         */
        $middleware->validateCsrfTokens(except: [
            'api/login',
            'api/logout',
        ]);

        /**
         * 🔥 DO NOT encrypt CloudFront cookies
         */
        $middleware->encryptCookies(except: [
            'CloudFront-Policy',
            'CloudFront-Signature',
            'CloudFront-Key-Pair-Id',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        /**
         * ✅ Always return JSON for API auth errors
         */
        $exceptions->render(function (
            AuthenticationException $e,
            Request $request
        ) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Unauthenticated'
                ], 401);
            }
        });
    })
    ->create();
