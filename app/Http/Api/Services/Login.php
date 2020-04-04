<?php

namespace App\Http\Api\Services;

use App\Services\AuthUser\Api as ApiAuthUser;
use App\Validators\Account as AccountValidator;

class Login extends Service
{

    public function loginByPassword($name, $password)
    {
        $validator = new AccountValidator();

        $user = $validator->checkUserLogin($name, $password);

        $authUser = new ApiAuthUser();

        return $authUser->saveAuthInfo($user);
    }

    public function loginByVerify($name, $code)
    {
        $validator = new AccountValidator();

        $user = $validator->checkVerifyLogin($name, $code);

        $authUser = new ApiAuthUser();

        return $authUser->saveAuthInfo($user);
    }

}
