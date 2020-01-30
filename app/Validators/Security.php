<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Util\Verification as VerificationUtil;
use App\Services\Captcha as CaptchaService;

class Security extends Validator
{

    public function checkVerifyCode($key, $code)
    {
        if (!VerificationUtil::checkCode($key, $code)) {
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
