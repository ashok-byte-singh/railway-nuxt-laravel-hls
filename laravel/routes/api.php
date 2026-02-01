<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\VideoController;
use App\Models\Experiment;

// use Illuminate\Support\Facades\Route;

Route::get('/__seed', function () {
    abort_unless(app()->environment('local'), 403);
    \Artisan::call('db:seed', ['--force' => true]);
    return 'seeded';
});

Route::get('/__migrate', function () {
    \Artisan::call('migrate', ['--force' => true]);
    return 'migrated';
});


Route::get('/health', function () {
    return response()->json([
        'status' => 'ok'
    ]);
});


Route::get('/volume-check', function () {
    return response()->json([
        'exists'   => is_dir('/data'),
        'writable' => is_writable('/data'),
        'content'  => scandir('/data'),
    ]);
});

Route::middleware(['web'])->group(function () {
    // Route::get('/cf-test', function () {
    //     return \App\Services\CloudFrontCookieSigner::sign('experiments/1', 60);
    // });
    

    // ---------- AUTH ----------
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/me', fn (Request $r) => $r->user());
        Route::post('/logout', [AuthController::class, 'logout']);

        // ---------- EXPERIMENT LIST ----------
        Route::get('/experiments', function () {
            return Experiment::where('is_active', true)->get();
        });

        // ---------- EXPERIMENT DETAIL ----------
        Route::get('/experiments/{experiment}', function (Experiment $experiment) {
            return response()->json([
                'id'        => $experiment->id,
                'title'     => $experiment->title,
                'aim'       => $experiment->aim,
                'objective' => $experiment->objective,
                'procedure' => $experiment->procedure,
                'video_url' => $experiment->video_url, // relative path only
            ]);
        });

        // ---------- VIDEO (SETS CLOUDFRONT COOKIES) ----------
        Route::get('/video/{experiment}', [VideoController::class, 'getVideo']);
    });
});
