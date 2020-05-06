<?php

namespace App\Services\Frontend\Account;

use App\Services\Frontend\Service;
use App\Validators\Account as AccountValidator;
use App\Validators\Verify as VerifyValidator;

class PasswordReset extends Service
{

    public function handle()
    {
        $post = $this->request->getPost();

        $accountValidator = new AccountValidator();

        $account = $accountValidator->checkLoginName($post['login_name']);

        $accountValidator->checkPassword($post['new_password']);

        $verifyValidator = new VerifyValidator();

        $verifyValidator->checkCode($post['login_name'], $post['verify_code']);

        $account->password = $post['new_password'];

        $account->update();

        return $account;
    }

}
