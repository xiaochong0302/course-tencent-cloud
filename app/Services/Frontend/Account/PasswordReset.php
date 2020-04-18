<?php

namespace App\Services\Frontend\Account;

use App\Services\Frontend\Service;
use App\Validators\Account as AccountValidator;
use App\Validators\Security as SecurityValidator;

class PasswordReset extends Service
{

    public function resetPassword()
    {
        $post = $this->request->getPost();

        $accountValidator = new AccountValidator();

        $account = $accountValidator->checkLoginName($post['name']);

        $accountValidator->checkPassword($post['new_password']);

        $securityValidator = new SecurityValidator();

        $securityValidator->checkVerifyCode($post['name'], $post['verify_code']);

        $account->password = $post['new_password'];

        $account->update();

        return $account;
    }

}
