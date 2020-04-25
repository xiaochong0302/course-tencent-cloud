<?php

namespace App\Services;

use App\Models\User as UserModel;

abstract class Auth extends Service
{

    abstract function saveAuthInfo(UserModel $user);

    /**
     * @return array|null
     */
    abstract function getAuthInfo();

    abstract function clearAuthInfo();

}
