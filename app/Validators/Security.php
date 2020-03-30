<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validator\Common as CommonValidator;
use App\Services\Captcha as CaptchaService;
use App\Services\Verification as VerificationService;

class Security extends Validator
{

    public function checkVerifyCode($key, $code)
    {
        $verification = new VerificationService();

        $result = false;

        if (CommonValidator::email($key)) {
            $result = $verification->checkMailCode($key, $code);
        } elseif (CommonValidator::phone($key)) {
            $result = $verification->checkSmsCode($key, $code);
        }

        if (!$result) {
            throw new BadRequestException('security.invalid_verify_code');
        }
    }

    public function checkCaptchaCode($ticket, $rand)
    {
        $captcha = new CaptchaService();

        $result = $captcha->verify($ticket, $rand);

        if (!$result) {
            throw new BadRequestException('security.invalid_captcha_code');
        }
    }

}
