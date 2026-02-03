<?php

return [
    'domain' => env('CDN_DOMAIN'),
    'signing_secret' => env('CDN_SIGNING_SECRET'),
    'ttl_seconds' => env('CDN_SIGNED_URL_TTL', 900),
];
