<?php

namespace App\Validators;

use App\Support\Captcha;

class CaptchaCode
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        return app(Captcha::class)->check($value);
    }
}
