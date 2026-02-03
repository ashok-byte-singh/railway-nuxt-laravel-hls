<?php

namespace App\Services;

class CloudflareSignedUrl
{
    public static function signPath(string $path, int $ttlSeconds): string
    {
        $secret = config('cdn.signing_secret');
        $domain = rtrim((string) config('cdn.domain'), '/');

        if (! $secret || ! $domain) {
            throw new \RuntimeException('CDN signing is not configured.');
        }

        $exp = time() + $ttlSeconds;
        $path = '/' . ltrim($path, '/');
        $data = "{$path}:{$exp}";
        $sig = self::base64UrlEncode(hash_hmac('sha256', $data, $secret, true));

        return "{$domain}{$path}?exp={$exp}&sig={$sig}";
    }

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
