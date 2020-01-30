<?php

namespace App\Validators;

use App\Exceptions\Forbidden as ForbiddenException;
use App\Exceptions\Unauthorized as UnauthorizedException;

class Validator extends \Phalcon\Mvc\User\Component
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

    public function checkOwnerPriv($userId, $ownerId)
    {
        if ($userId != $ownerId) {
            throw new ForbiddenException('sys.access_denied');
        }
    }

}
