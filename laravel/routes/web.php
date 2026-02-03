<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;

Route::get(
    '/hls/segment/{experiment}/{file}',
    [VideoController::class, 'segment']
)->where('file', '.*');
