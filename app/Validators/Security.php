<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\ServiceUnavailable as ServiceUnavailableException;
use App\Services\Throttle as ThrottleService;

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

}
