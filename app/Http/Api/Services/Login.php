<?php

namespace App\Http\Api\Services;

use App\Services\Auth\Api as ApiAuth;
use App\Validators\Account as AccountValidator;

class Login extends Service
{

    public function loginByPassword($name, $password)
    {
        $validator = new AccountValidator();

        $user = $validator->checkUserLogin($name, $password);

        $auth = new ApiAuth();

        return $auth->saveAuthInfo($user);
    }

    public function loginByVerify($name, $code)
    {
        $validator = new AccountValidator();

        $user = $validator->checkVerifyLogin($name, $code);

        $auth = new ApiAuth();

        return $auth->saveAuthInfo($user);
    }

}
