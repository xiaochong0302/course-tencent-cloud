<?php

namespace App\Http\Api\Services;

use App\Services\Auth\Api as ApiAuth;

class Logout extends Service
{

    public function logout()
    {
        $auth = new ApiAuth();

        return $auth->clearAuthInfo();
    }

}
