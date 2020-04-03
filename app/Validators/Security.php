<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validator\Common as CommonValidator;
use App\Services\Captcha as CaptchaService;
use App\Services\VerifyCode as VerifyCodeService;

class Security extends Validator
{

    public function checkVerifyCode($key, $code)
    {
        $verifyCodeService = new VerifyCodeService();

        $result = false;

        if (CommonValidator::email($key)) {
            $result = $verifyCodeService->checkMailCode($key, $code);
        } elseif (CommonValidator::phone($key)) {
            $result = $verifyCodeService->checkSmsCode($key, $code);
        }

        if (!$result) {
            throw new BadRequestException('security.invalid_verify_code');
        }
    }

    public function checkCaptchaCode($ticket, $rand)
    {
        $captchaService = new CaptchaService();

        $result = $captchaService->verify($ticket, $rand);

        if (!$result) {
            throw new BadRequestException('security.invalid_captcha_code');
        }
    }

}
