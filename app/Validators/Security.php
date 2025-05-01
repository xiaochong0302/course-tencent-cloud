<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\CsrfToken as CsrfTokenService;

class Security extends Validator
{

    public function checkCsrfToken()
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

    public function checkHttpReferer()
    {
        $refererHost = parse_url($this->request->getHttpReferer(), PHP_URL_HOST);

        $httpHost = preg_replace('/:\d+/', '', $this->request->getHttpHost());

        if ($refererHost != $httpHost) {
            throw new BadRequestException('security.invalid_http_referer');
        }
    }

    protected function getCsrfWhitelist()
    {
        return [];
    }

}
