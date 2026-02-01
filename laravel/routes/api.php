<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\VideoController;
use App\Models\Experiment;

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



Route::get('/volume-check', function () {
    return response()->json([
        'exists'   => is_dir('/data'),
        'writable' => is_writable('/data'),
        'content'  => scandir('/data'),
    ]);
});

/*
|--------------------------------------------------------------------------
| Auth (NO web middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['web'])->group(function () {
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| Authenticated API
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', fn (Request $r) => $r->user());

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

    Route::get('/video/{experiment}', [VideoController::class, 'getVideo']);
});
});