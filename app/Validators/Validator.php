<?php

namespace App\Validators;

use App\Exceptions\Forbidden as ForbiddenException;
use App\Exceptions\Unauthorized as UnauthorizedException;
use Phalcon\Mvc\User\Component;

class Validator extends Component
{

    public function checkAuthUser($authUser)
    {
        if (empty($authUser['id'])) {
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
