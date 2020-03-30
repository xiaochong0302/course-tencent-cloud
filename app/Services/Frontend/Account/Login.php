<?php

namespace App\Services\Frontend\Account;

use App\Services\Frontend\Service;
use App\Validators\Account as AccountValidator;
use App\Validators\Security as SecurityValidator;

class Login extends Service
{

    public function loginByPassword($account, $password)
    {
        $validator = new AccountValidator();

        $user = $validator->checkUserLogin($account, $password);

        return $user;
    }

    public function loginByVerify($account, $code)
    {
        $validator = new SecurityValidator();

        $validator->checkVerifyCode($account, $code);
    }

}
