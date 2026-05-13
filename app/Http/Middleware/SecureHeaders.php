<?php

namespace App\Http\Middleware;

use Closure;

class SecureHeaders
{
    // Enumerate headers which you do not want in your application's responses.
    // Great starting point would be to go check out @Scott_Helme's:
    // https://securityheaders.com/
    private $unwantedHeaderList = [
        'X-Powered-By',
        'Server',
    ];

    public function handle($request, Closure $next)
    {
        $this->removeUnwantedHeaders($this->unwantedHeaderList);
        $response = $next($request);
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Feature-Policy', "accelerometer 'none'; camera 'none'; geolocation 'none'; gyroscope 'none'; magnetometer 'none'; microphone 'none'; payment 'none'; usb 'none'");
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        $cspDefaultSrc = "default-src 'self' https://*.biomagnetism.app wss://*.firebaseio.com https://sentry.io https://*.paypal.com";
        $cspFontSrc = "font-src 'self' data: https://fonts.gstatic.com";
        $cspImageSrc = "img-src 'self' data: https://*.biomagnetism.app https://*.amazonaws.com https://www.gstatic.com https://www.googletagmanager.com https://www.google-analytics.com https://cdn.gtranslate.net https://*.gtranslate.net";
        $cspMediaSrc = "media-src 'self' https://*.biomagnetism.app https://*.amazonaws.com";
        $cspScriptSrc = "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.ravenjs.com https://cdn.gtranslate.net https://cdn.jsdelivr.net https://code.jquery.com";
        $cspStyleSrc = "style-src 'self' 'unsafe-inline' https://cdn.gtranslate.net https://fonts.googleapis.com https://cdn.jsdelivr.net";

        if (app()->environment('production')) {
            $cspDefaultSrc = $cspDefaultSrc . " https: ; upgrade-insecure-requests;";
            $cspFontSrc = $cspFontSrc . " https:";
            $cspMediaSrc = $cspMediaSrc . " https:";
            $cspScriptSrc = $cspScriptSrc . " https:";
            $cspStyleSrc = $cspStyleSrc . " https:";

            if (!empty(env('SENTRY_REPORT_URI'))) {
                $reportUri = "report-uri " . env('SENTRY_REPORT_URI');
            }
        }

        if (empty($reportUri)) {
            $cspHeaders = [$cspDefaultSrc, $cspFontSrc, $cspImageSrc, $cspMediaSrc, $cspScriptSrc, $cspStyleSrc];
        } else {
            $cspHeaders = [$cspDefaultSrc, $cspFontSrc, $cspImageSrc, $cspMediaSrc, $cspScriptSrc, $cspStyleSrc, $reportUri];
        }

        $cspHeaders = implode('; ', $cspHeaders);
        $response->headers->set('Content-Security-Policy', $cspHeaders);

        return $response;
    }

    private function removeUnwantedHeaders($headerList)
    {
        foreach ($headerList as $header) {
            header_remove($header);
        }
    }
}
