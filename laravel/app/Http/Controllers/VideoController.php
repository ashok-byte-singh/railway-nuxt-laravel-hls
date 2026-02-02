<?php

namespace App\Http\Controllers;

use App\Models\Experiment;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function getVideo(Experiment $experiment)
    {
        // 1️⃣ Original playlist path in MinIO
        $path = ltrim($experiment->video_url, '/');

        if (!Storage::disk('s3')->exists($path)) {
            abort(404, 'Playlist not found');
        }

        // 2️⃣ Read playlist
        $playlist = Storage::disk('s3')->get($path);

        // 3️⃣ Rewrite TS URLs → Laravel proxy
        $playlist = preg_replace_callback(
            '/\n([^#\n]+\.ts)/',
            function ($m) use ($experiment) {
                return "\n" . url("/hls/segment/{$experiment->id}/{$m[1]}");
            },
            $playlist
        );

        // 4️⃣ Return HLS playlist
        return response($playlist, 200, [
            'Content-Type' => 'application/vnd.apple.mpegurl',
            'Cache-Control' => 'no-store',
        ]);
    }

    public function segment(int $experiment, string $file)
    {
        $path = "experiments/{$experiment}/{$file}";

        if (!Storage::disk('s3')->exists($path)) {
            abort(404);
        }

        return response()->stream(function () use ($path) {
            $stream = Storage::disk('s3')->readStream($path);
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => 'video/mp2t',
            'Cache-Control' => 'no-store',
        ]);
    }
}
