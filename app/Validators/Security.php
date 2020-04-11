<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\ServiceUnavailable as ServiceUnavailableException;
use App\Library\Validator\Common as CommonValidator;
use App\Services\Captcha as CaptchaService;
use App\Services\Throttle as ThrottleService;
use App\Services\VerifyCode as VerifyCodeService;

class Security extends Validator
{

    public function checkCsrfToken()
    {
        $tokenKey = $this->request->getHeader('X-Csrf-Token-Key');
        $tokenValue = $this->request->getHeader('X-Csrf-Token-Value');

        $result = $this->security->checkToken($tokenKey, $tokenValue);

        if (!$result) {
            throw new BadRequestException('security.invalid_csrf_token');
        }
    }

    public function checkHttpReferer()
    {
        $httpHost = parse_url($this->request->getHttpReferer(), PHP_URL_HOST);

        $result = $httpHost == $this->request->getHttpHost();

        if (!$result) {
            throw new BadRequestException('security.invalid_http_referer');
        }
    }

    public function checkRateLimit()
    {
        $throttleService = new ThrottleService();

        $result = $throttleService->checkRateLimit();

        if (!$result) {
            throw new ServiceUnavailableException('security.too_many_requests');
        }
    }

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
