<?php
namespace App\Http\Controllers;

use App\Models\Experiment;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function getVideo(Experiment $experiment)
    {
        if (! $experiment->video_url) {
            abort(404);
        }

        // Example: experiments/2/video2.m3u8
        $path = ltrim($experiment->video_url, '/');

        $playlist = Storage::disk('s3')->get($path);

        // Rewrite .ts → Laravel proxy route
        $playlist = preg_replace_callback(
            '/^(.+\.ts)$/m',
            function ($m) use ($experiment) {
                return url("/hls/segment/{$experiment->id}/{$m[1]}");
            },
            $playlist
        );

        return response($playlist, 200, [
            'Content-Type' => 'application/vnd.apple.mpegurl',
            'Cache-Control' => 'no-store',
        ]);
    }
}
