<?php
namespace App\Http\Controllers;

use App\Models\Experiment;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function getVideo(Experiment $experiment)
{
    $path = ltrim($experiment->video_url, '/'); // experiments/1/video.m3u8

    $playlist = Storage::disk('s3')->get($path);

    $playlist = preg_replace_callback(
        '/^(.+\.ts)$/m',
        fn ($m) => url("/hls/segment/{$experiment->id}/{$m[1]}"),
        $playlist
    );

    return response($playlist, 200, [
        'Content-Type' => 'application/vnd.apple.mpegurl',
        'Cache-Control' => 'no-store',
    ]);
}


}
