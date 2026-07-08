<?php

namespace App\Http\Middleware;

use Closure;

class SiteAccessGate
{
    const COOKIE_NAME = 'site_access_unlocked';

    public function handle($request, Closure $next)
    {
        $configured = (string) config('app.site_access_password');

        if ($configured === '') {
            return $next($request);
        }

        if ($request->is('site-access/verify')) {
            return $next($request);
        }

        $expected = hash_hmac('sha256', $configured, config('app.key'));
        $cookie = (string) $request->cookie(self::COOKIE_NAME);

        if ($cookie !== '' && hash_equals($expected, $cookie)) {
            return $next($request);
        }

        return response()->view('site_access.gate', [], 200)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }
}
