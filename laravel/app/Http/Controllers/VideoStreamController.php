<?php

namespace App\Http\Controllers;

use App\Services\CloudFrontCookieSigner;
use Illuminate\Http\Response;

class VideoStreamController extends Controller
{
    public function stream()
    {
        $cookies = CloudFrontCookieSigner::sign('experiments/1', 60);
        $domain  = config('services.cloudfront.domain');

        return response()->json([
            'playlist' => "https://{$domain}/experiments/1/video1.m3u8"
        ])
        ->cookie('CloudFront-Policy', $cookies['CloudFront-Policy'], 60, '/', $domain, true, true, false, 'None')
        ->cookie('CloudFront-Signature', $cookies['CloudFront-Signature'], 60, '/', $domain, true, true, false, 'None')
        ->cookie('CloudFront-Key-Pair-Id', $cookies['CloudFront-Key-Pair-Id'], 60, '/', $domain, true, true, false, 'None');
    }
}
