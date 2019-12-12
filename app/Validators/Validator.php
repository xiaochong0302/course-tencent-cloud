<?php

namespace App\Validators;

use App\Exceptions\Forbidden as ForbiddenException;
use App\Exceptions\Unauthorized as UnauthorizedException;
use Phalcon\Mvc\User\Component as UserComponent;

class Validator extends UserComponent
{

    public function checkAuthToken($token)
    {
        if (!$token) {
            throw new UnauthorizedException('sys.invalid_auth_token');
        }

        return $token;
    }

    public function checkAuthUser($user)
    {
        if (!$user) {
            throw new UnauthorizedException('sys.auth_user_failed');
        }

        return $user;
    }

    public function checkOwner($itemUserId, $sessionUserId)
    {
        if ($itemUserId != $sessionUserId) {
            throw new ForbiddenException('access_denied');
        }
    }

}
