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

        // Stored in DB:
        // experiments/1/index.m3u8
        // $path = ltrim($experiment->video_url, '/');

        // Generate signed MinIO URL
        $signedUrl = Storage::disk('s3')->temporaryUrl(
            $path,
            now()->addMinutes(15)
        );

        return response()->json([
            'playlist' => $signedUrl,
            'expires_in' => 900, // seconds
            'type' => 'signed_hls',
        ]);
    }
}
