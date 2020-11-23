<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\ServiceUnavailable as ServiceUnavailableException;
use App\Library\CsrfToken as CsrfTokenService;
use App\Services\Throttle as ThrottleService;

class Security extends Validator
{

    public function checkCsrfToken()
    {
        $token = $this->request->getHeader('X-Csrf-Token');

        $service = new CsrfTokenService();

        $result = $service->checkToken($token);

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
