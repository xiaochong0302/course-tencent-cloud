<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\ServiceUnavailable as ServiceUnavailableException;
use App\Library\CsrfToken as CsrfTokenService;
use App\Services\Throttle as ThrottleService;

class Security extends Validator
{

    public function checkCsrfToken()
    {
        $route = $this->router->getMatchedRoute();

        if (in_array($route->getName(), $this->getCsrfWhitelist())) {
            return;
        }

        $token = $this->request->getHeader('X-Csrf-Token');

        $service = new CsrfTokenService();

        $result = $service->checkToken($token);

        if (!$result) {
            throw new BadRequestException('security.invalid_csrf_token');
        }
    }

    public function checkHttpReferer()
    {
        $refererHost = parse_url($this->request->getHttpReferer(), PHP_URL_HOST);

        $httpHost = preg_replace('/:\d+/', '', $this->request->getHttpHost());

        if ($refererHost != $httpHost) {
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

    protected function getCsrfWhitelist()
    {
        return [
            'admin.upload.content_img',
            'admin.upload.remote_img',
            'home.upload.content_img',
            'home.upload.remote_img',
        ];
    }

}
