<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Models\Experiment;
use App\Http\Controllers\Api\AuthController;


// use App\Http\Controllers\Api\AuthController;

// Route::middleware(['web'])->group(function () {

//     Route::post('/login', [AuthController::class, 'login']);
//     Route::post('/logout', [AuthController::class, 'logout'])
//         ->middleware('auth:sanctum');

//     Route::get('/me', fn (Request $r) => $r->user())
//         ->middleware('auth:sanctum');
// });

Route::get(
    '/hls/segment/{experiment}/{file}',
    [VideoController::class, 'segment']
)->where('file', '.*');


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
