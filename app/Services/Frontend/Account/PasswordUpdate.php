<?php

namespace App\Services\Frontend\Account;

use App\Repos\Account as AccountRepo;
use App\Services\Frontend\Service;
use App\Validators\Account as AccountValidator;

class PasswordUpdate extends Service
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

        $account->password = $newPassword;

        $account->update();

        return $account;
    }

}
