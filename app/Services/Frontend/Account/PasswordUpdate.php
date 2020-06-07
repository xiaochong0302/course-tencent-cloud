<?php

namespace App\Services\Frontend\Account;

use App\Library\Utils\Password as PasswordUtil;
use App\Repos\Account as AccountRepo;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\Account as AccountValidator;

class PasswordUpdate extends FrontendService
{

    public function handle()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById($user->id);

        $accountValidator = new AccountValidator();

        $accountValidator->checkOriginPassword($account, $post['origin_password']);

        $newPassword = $accountValidator->checkPassword($post['new_password']);

        $accountValidator->checkConfirmPassword($post['new_password'], $post['confirm_password']);

        $salt = PasswordUtil::salt();
        $password = PasswordUtil::hash($newPassword, $salt);

        $account->salt = $salt;
        $account->password = $password;

        $account->update();

        return $account;
    }

}
