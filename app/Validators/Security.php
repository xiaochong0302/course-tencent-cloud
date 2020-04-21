<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\ServiceUnavailable as ServiceUnavailableException;
use App\Library\Validator\Common as CommonValidator;
use App\Services\Captcha as CaptchaService;
use App\Services\Throttle as ThrottleService;
use App\Services\Verification as VerificationService;

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
        $service = new ThrottleService();

        $result = $service->checkRateLimit();

        if (!$result) {
            throw new ServiceUnavailableException('security.too_many_requests');
        }
    }

    public function checkVerifyCode($key, $code)
    {
        $service = new VerificationService();

        $result = false;

        if (CommonValidator::email($key)) {
            $result = $service->checkMailCode($key, $code);
        } elseif (CommonValidator::phone($key)) {
            $result = $service->checkSmsCode($key, $code);
        }

        if (!$result) {
            throw new BadRequestException('security.invalid_verify_code');
        }
    }

    public function checkCaptchaCode($ticket, $rand)
    {
        $service = new CaptchaService();

        $result = $service->verify($ticket, $rand);

        if (!$result) {
            throw new BadRequestException('security.invalid_captcha_code');
        }
    }

}
