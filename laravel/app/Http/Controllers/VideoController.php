<?php

namespace App\Http\Controllers;

use App\Models\Experiment;
use App\Services\CloudFrontCookieSigner;

class VideoController extends Controller
{
    public function getVideo(Experiment $experiment)
    {
        // CloudFront CNAME, not the CF hostname
        $cdnDomain = config('services.cloudfront.domain'); 
        // example: cdn.example.com

        // Parent domain you own
        $cookieDomain = '.' . config('app.domain'); 
        // example: .example.com

        // example: experiments/1/video.m3u8
        $videoPath = ltrim($experiment->video_url, '/');

        // IMPORTANT: sign DIRECTORY, not file
        $directory = dirname($videoPath); // experiments/1

        $cookies = CloudFrontCookieSigner::sign($directory, 60);

        return response()->json([
            'playlist' => "https://{$cdnDomain}/{$videoPath}",
            'type'     => 'signed_cookies'
        ])
        ->cookie(
            'CloudFront-Policy',
            $cookies['CloudFront-Policy'],
            60,
            '/',
            $cookieDomain,
            true,
            true,
            false,
            'None'
        )
        ->cookie(
            'CloudFront-Signature',
            $cookies['CloudFront-Signature'],
            60,
            '/',
            $cookieDomain,
            true,
            true,
            false,
            'None'
        )
        ->cookie(
            'CloudFront-Key-Pair-Id',
            $cookies['CloudFront-Key-Pair-Id'],
            60,
            '/',
            $cookieDomain,
            true,
            true,
            false,
            'None'
        );
    }
}
