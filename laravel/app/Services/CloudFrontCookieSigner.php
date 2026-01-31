<?php

namespace App\Services;

use Carbon\Carbon;

class CloudFrontCookieSigner
{
    public static function sign(string $directory, int $minutes = 60): array
    {
        $domain     = config('services.cloudfront.domain');
        $keyPairId  = config('services.cloudfront.key_pair_id');
        $privateKey = file_get_contents(storage_path('keys/cloudfront-private.pem'));

        $expires = Carbon::now()->addMinutes($minutes)->timestamp;

        $directory = trim($directory, '/');

        $policy = json_encode([
            'Statement' => [[
                'Resource' => "https://{$domain}/{$directory}/*",
                'Condition' => [
                    'DateLessThan' => [
                        'AWS:EpochTime' => $expires
                    ]
                ]
            ]]
        ], JSON_UNESCAPED_SLASHES);

        openssl_sign($policy, $signature, $privateKey, OPENSSL_ALGO_SHA1);

        return [
            'CloudFront-Policy'      => self::base64UrlEncode($policy),
            'CloudFront-Signature'   => self::base64UrlEncode($signature),
            'CloudFront-Key-Pair-Id' => $keyPairId,
        ];
    }

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
