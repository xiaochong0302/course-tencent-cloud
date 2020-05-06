<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Services\Captcha as CaptchaService;

class Captcha extends Validator
{

    public function checkCode($ticket, $rand)
    {
        $service = new CaptchaService();

        $result = $service->verify($ticket, $rand);

        if (!$result) {
            throw new BadRequestException('captcha.invalid_code');
        }
    }

}
