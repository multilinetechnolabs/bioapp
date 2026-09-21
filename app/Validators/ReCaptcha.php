<?php

namespace App\Validators;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReCaptcha
{
    const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    // Fails closed: any problem reaching or reading Google's reply (timeout, non-JSON
    // body, missing secret) counts as a failed check instead of throwing, so a Google
    // outage shows a validation message rather than a 500 page.
    public function validate($attribute, $value, $parameters, $validator)
    {
        $secret = config('services.recaptcha.secret_key');

        if (empty($secret)) {
            Log::error('reCAPTCHA secret key is not configured (GOOGLE_RECAPTCHA_SECRET_KEY).');

            return false;
        }

        if (empty($value) || !is_string($value)) {
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(5)
                ->post(self::VERIFY_URL, [
                    'secret' => $secret,
                    'response' => $value,
                ]);
        } catch (\Throwable $e) {
            Log::warning('reCAPTCHA verification request failed.', ['exception' => $e->getMessage()]);

            return false;
        }

        $body = $response->json();

        if (!$response->successful() || ($body['success'] ?? false) !== true) {
            // Google's own reason ("invalid-input-secret", "timeout-or-duplicate", ...) is the only
            // way to tell a bad key pair from an expired token, so keep it in the log.
            Log::warning('reCAPTCHA verification rejected.', [
                'http_status' => $response->status(),
                'error_codes' => $body['error-codes'] ?? null,
                'hostname' => $body['hostname'] ?? null,
            ]);

            return false;
        }

        return true;
    }
}
