<?php

namespace App\Http\Controllers;

use App\Http\Middleware\SiteAccessGate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class SiteAccessController extends Controller
{
    public function verify(Request $request)
    {
        $configured = (string) config('app.site_access_password');
        $submitted = (string) $request->input('password');

        if ($configured === '' || !hash_equals($configured, $submitted)) {
            return response()->json(['ok' => false, 'message' => 'Incorrect password.'], 422);
        }

        $token = hash_hmac('sha256', $configured, config('app.key'));

        // 0 minutes = session cookie: cleared automatically when the browser fully closes.
        Cookie::queue(SiteAccessGate::COOKIE_NAME, $token, 0);

        return response()->json(['ok' => true]);
    }
}
