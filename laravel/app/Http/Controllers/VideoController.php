<?php

namespace App\Http\Controllers;

use App\Models\Experiment;

class VideoController extends Controller
{
    public function getVideo(Experiment $experiment)
    {
        if (! $experiment->video_url) {
            abort(404, 'Video not available');
        }

        // video_url example stored in DB:
        // experiments/1/index.m3u8
        $relativePath = ltrim($experiment->video_url, '/');

        return response()->json([
            'playlist' => url('/hls/' . $relativePath),
            'type' => 'public_hls',
        ]);
    }
}
