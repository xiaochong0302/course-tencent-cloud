<?php

namespace App\Services\Frontend\Account;

use App\Services\Frontend\Service;
use App\Validators\Account as AccountValidator;
use App\Validators\Verify as VerifyValidator;

class PasswordResetByPhone extends Service
{

    public function handle()
    {
        $post = $this->request->getPost();

        $accountValidator = new AccountValidator();

        $account = $accountValidator->checkAccountByPhone($post['phone']);

        $accountValidator->checkPassword($post['new_password']);

        $verifyValidator = new VerifyValidator();

        $verifyValidator->checkSmsCode($post['phone'], $post['verify_code']);

        $account->password = $post['new_password'];

        $account->update();

        return $account;
    }

}
