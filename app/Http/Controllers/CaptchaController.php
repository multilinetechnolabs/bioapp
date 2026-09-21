<?php

namespace App\Http\Controllers;

use App\Support\Captcha;

class CaptchaController extends Controller
{
    // Every request starts a new code, so the image must never be cached.
    public function image(Captcha $captcha)
    {
        return response($captcha->make(), 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }
}
