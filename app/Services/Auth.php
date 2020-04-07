<?php

namespace App\Services;

use App\Models\User as UserModel;

abstract class Auth extends Service
{

    abstract function saveAuthInfo(UserModel $user);

    abstract function getAuthInfo();

    abstract function clearAuthInfo();

}
