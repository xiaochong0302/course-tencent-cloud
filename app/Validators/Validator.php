<?php

namespace App\Validators;

use App\Exceptions\Forbidden as ForbiddenException;
use App\Exceptions\Unauthorized as UnauthorizedException;
use Phalcon\Mvc\User\Component;

class Validator extends Component
{

    public function checkAuthUser($user)
    {
        if (empty($user['id'])) {
            throw new UnauthorizedException('sys.auth_user_failed');
        }

        return $user;
    }

    public function checkOwner($userId, $ownerId)
    {
        if ($userId != $ownerId) {
            throw new ForbiddenException('sys.access_denied');
        }
    }

}
