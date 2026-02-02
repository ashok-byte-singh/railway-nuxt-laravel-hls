<?php

namespace App\Http\Controllers;

use App\Models\Experiment;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function getVideo(Experiment $experiment)
    {
        if (! $experiment->video_url) {
            abort(404, 'Video not available');
        }

        // e.g. experiments/2/video2.m3u8
        $path = ltrim($experiment->video_url, '/');

        // Load original playlist
        $playlist = Storage::disk('s3')->get($path);

        // Base directory: experiments/2
        $baseDir = dirname($path);

        // Rewrite ONLY .ts lines
        $playlist = preg_replace_callback(
            '/^(.+\.ts)$/m',
            function ($matches) use ($baseDir) {
                return Storage::disk('s3')->temporaryUrl(
                    $baseDir . '/' . $matches[1],
                    now()->addMinutes(10)
                );
            },
            $playlist
        );

        return response($playlist, 200, [
            'Content-Type' => 'application/vnd.apple.mpegurl',
            'Cache-Control' => 'no-cache',
        ]);
    }
}
