<?php

namespace App\Services\Frontend\Account;

use App\Library\Utils\Password as PasswordUtil;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\Account as AccountValidator;
use App\Validators\Verify as VerifyValidator;

class PasswordReset extends FrontendService
{

    public function handle()
    {
        $post = $this->request->getPost();

        $accountValidator = new AccountValidator();

        $account = $accountValidator->checkAccount($post['account']);

        $newPassword = $accountValidator->checkPassword($post['new_password']);

        $verifyValidator = new VerifyValidator();

        $verifyValidator->checkCode($post['account'], $post['verify_code']);

        $salt = PasswordUtil::salt();
        $password = PasswordUtil::hash($newPassword, $salt);

        $account->salt = $salt;
        $account->password = $password;

        $account->update();

        return $account;
    }

}
