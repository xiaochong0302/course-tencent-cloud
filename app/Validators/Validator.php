<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\Forbidden as ForbiddenException;
use App\Exceptions\Unauthorized as UnauthorizedException;
use App\Traits\Service as ServiceTrait;
use Phalcon\Di\Injectable;

class Validator extends Injectable
{

    use ServiceTrait;

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
