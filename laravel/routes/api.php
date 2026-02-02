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
                    abort(404, 'Segment not found');
                }
            
                return response(
                    Storage::disk('s3')->get($path),
                    200,
                    [
                        'Content-Type' => 'video/mp2t',
                        'Cache-Control' => 'no-store',
                    ]
                );
            });
               
            

    });
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

    // Route::get('/video/{experiment}', [VideoController::class, 'getVideo']);
});
Route::get('/video/{experiment}', [VideoController::class, 'getVideo']);

