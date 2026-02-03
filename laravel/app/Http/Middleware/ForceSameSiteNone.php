<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;

class ForceSameSiteNone
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $sessionCookie = config('session.cookie');
        $xsrfCookie = config('sanctum.cookie', 'XSRF-TOKEN');

        foreach ($response->headers->getCookies() as $cookie) {
            $name = $cookie->getName();

            if ($name !== $sessionCookie && $name !== $xsrfCookie) {
                continue;
            }

            // Remove existing cookie so we can re-set with SameSite=None; Secure.
            $response->headers->removeCookie(
                $name,
                $cookie->getPath(),
                $cookie->getDomain()
            );

            $response->headers->setCookie(new Cookie(
                $cookie->getName(),
                $cookie->getValue(),
                $cookie->getExpiresTime() ?: 0,
                $cookie->getPath(),
                $cookie->getDomain(),
                true, // secure
                $cookie->isHttpOnly(),
                $cookie->isRaw(),
                'None'
            ));
        }

        return $response;
    }
}
