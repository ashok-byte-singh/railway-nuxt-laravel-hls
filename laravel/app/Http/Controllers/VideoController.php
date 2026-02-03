<?php

namespace App\Http\Controllers;

use App\Models\Experiment;
use App\Services\CloudflareSignedUrl;
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

        $baseDir = trim(dirname($path), '.');
        $bucket = (string) config('filesystems.disks.s3.bucket');

        // 3️⃣ Rewrite TS URLs → Cloudflare signed CDN URLs
        $playlist = preg_replace_callback(
            '/^(?!#)(.+\.ts)$/m',
            function ($m) use ($baseDir, $bucket) {
                $line = trim($m[1]);

                if (str_starts_with($line, 'http://') || str_starts_with($line, 'https://')) {
                    return $line;
                }

                $path = ltrim($bucket . '/' . $baseDir . '/' . ltrim($line, '/'), '/');
                $ttl = (int) config('cdn.ttl_seconds', 900);

                return CloudflareSignedUrl::signPath($path, $ttl);
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
