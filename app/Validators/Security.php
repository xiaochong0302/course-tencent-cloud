<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\Forbidden as ForbiddenException;
use App\Exceptions\ServiceUnavailable as ServiceUnavailableException;
use App\Library\CsrfToken as CsrfTokenService;
use App\Services\ThrottleLimit as ThrottleLimitService;

class Security extends Validator
{

    public function checkClientAddress(): void
    {
        $address = $this->request->getClientAddress();

        $settings = $this->getSettings('security.blacklist');

        if ($settings['enabled'] == 0) return;

        $settings['content'] = str_replace('，', ',', $settings['content']);

        $blacklist = explode(',', $settings['content']);

        if (in_array($address, $blacklist)) {
            throw new ForbiddenException('security.client_address_blocked');
        }
    }

    public function checkCsrfToken(): bool
    {
        $route = $this->router->getMatchedRoute();
        $headerToken = $this->request->getHeader('X-Csrf-Token');
        $postToken = $this->request->getPost('csrf_token');

        if (in_array($route->getName(), $this->getCsrfWhitelist())) {
            return true;
        }

        $service = new CsrfTokenService();

        $result = false;

        if ($headerToken) {
            $result = $service->checkToken($headerToken);
        } elseif ($postToken) {
            $result = $service->checkToken($postToken);
        }

        if (!$result) {
            throw new BadRequestException('security.invalid_csrf_token');
        }

        return true;
    }

    public function checkHttpReferer(): void
    {
        $refererHost = parse_url($this->request->getHttpReferer(), PHP_URL_HOST);

        $httpHost = preg_replace('/:\d+/', '', $this->request->getHttpHost());

        if ($refererHost != $httpHost) {
            throw new BadRequestException('security.invalid_http_referer');
        }
    }

    public function checkRateLimit(): void
    {
        $service = new ThrottleLimitService();

        $result = $service->checkRateLimit();

        if (!$result) {
            throw new ServiceUnavailableException('security.too_many_requests');
        }
    }

    protected function getCsrfWhitelist(): array
    {
        return [
            'home.danmu.create',
        ];
    }

}
