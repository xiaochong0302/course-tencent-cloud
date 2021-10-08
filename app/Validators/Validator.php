<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\Forbidden as ForbiddenException;
use App\Exceptions\ServiceUnavailable;
use App\Exceptions\ServiceUnavailable as ServiceUnavailableException;
use App\Exceptions\Unauthorized as UnauthorizedException;
use App\Services\Service as AppService;
use Phalcon\Di\Injectable;

class Validator extends Injectable
{

    public function checkSiteStatus()
    {
        $service = new AppService();

        $siteInfo = $service->getSettings('site');

        if ($siteInfo['status'] == 'closed') {
            throw new ServiceUnavailableException('sys.service_unavailable');
        }
    }

    public function checkAuthUser($userId)
    {
        if (empty($userId)) {
            throw new UnauthorizedException('sys.unauthorized');
        }
    }

    public function checkOwner($userId, $ownerId)
    {
        if ($userId != $ownerId) {
            throw new ForbiddenException('sys.forbidden');
        }
    }

}
