<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\VideoController;
use App\Models\Experiment;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| Utility / Internal
|--------------------------------------------------------------------------
*/

Route::get('/health', fn () => response()->json(['status' => 'ok']));



Route::get('/__migrate', function () {
    \Artisan::call('migrate', ['--force' => true]);
    return 'migrated';
});

Route::get('/__seed', function () {
    \Artisan::call('db:seed', ['--force' => true]);
    return 'seeded';
});

Route::get('/__config-clear', function () {
    \Artisan::call('config:clear');
    return 'config cleared';
});

Route::get('/__cache-clear', function () {
    \Artisan::call('cache:clear');
    return 'cache cleared';
});

Route::get('/__route-clear', function () {
    \Artisan::call('route:clear');
    return 'route cleared';
});

Route::get('/__optimize-clear', function () {
    \Artisan::call('optimize:clear');
    return 'optimize cleared';
});

Route::get('/__view-clear', function () {
    \Artisan::call('view:clear');
    return 'view cleared';
});

Route::get('/__session-config', function () {
    return response()->json([
        'same_site' => config('session.same_site'),
        'secure' => config('session.secure'),
        'driver' => config('session.driver'),
        'domain' => config('session.domain'),
        'path' => config('session.path'),
        'cookie' => config('session.cookie'),
        'env_same_site' => env('SESSION_SAME_SITE'),
        'env_secure' => env('SESSION_SECURE_COOKIE'),
        'env_driver' => env('SESSION_DRIVER'),
        'config_cached' => app()->configurationIsCached(),
        'cookie_provider_loaded' => app()->providerIsLoaded(App\Providers\CookieServiceProvider::class),
        'app_provider_loaded' => app()->providerIsLoaded(App\Providers\AppServiceProvider::class),
    ]);
});

/*
|--------------------------------------------------------------------------
| MinIO test (temporary)
|--------------------------------------------------------------------------
*/

Route::get('/minio-test', function () {
    Storage::disk('s3')->put(
        'test/hello.txt',
        'MinIO working 🎉'
    );

    return response()->json(['status' => 'ok']);
});

// Route::get(
//     '/hls/segment/{experiment}/{file}',
//     [VideoController::class, 'segment']
// )->where('file', '.*');

/*
|--------------------------------------------------------------------------
| Authenticated API (COOKIE-BASED via Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware(['web'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth:sanctum');

    Route::get('/me', fn (Request $r) => $r->user())
        ->middleware('auth:sanctum');

    Route::get('/hls/segment/{experiment}/{segment}', function (
        Experiment $experiment,
        string $segment
    ) {
        $path = "experiments/{$experiment->id}/{$segment}";

        if (!Storage::disk('s3')->exists($path)) {
            abort(404, 'Segment not found in bucket');
        }

        return response(
            Storage::disk('s3')->get($path),
            200,
            ['Content-Type' => 'video/mp2t']
        );
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/experiments', function () {
            return Experiment::where('is_active', true)->get();
        });

        Route::get('/experiments/{experiment}', function (Experiment $experiment) {
            return response()->json([
                'id'        => $experiment->id,
                'title'     => $experiment->title,
                'aim'       => $experiment->aim,
                'objective' => $experiment->objective,
                'procedure' => $experiment->procedure,
                'video_url' => $experiment->video_url,
            ]);
        });

        // Route::get('/video/{experiment}', [VideoController::class, 'getVideo']);
    });
});
Route::get('/video/{experiment}', [VideoController::class, 'getVideo']);
