<?php

namespace App\Http\Api\Services;

use App\Services\AuthUser\Api as ApiAuthUser;

class Logout extends Service
{

    public function logout()
    {
        $authUser = new ApiAuthUser();

        return $authUser->clearAuthInfo();
    }

}
