<?php

namespace App\Services\Frontend;

use App\Repos\Account as AccountRepo;
use App\Validators\Account as AccountValidator;
use App\Validators\Security as SecurityValidator;

class EmailUpdate extends Service
{

    public function updateEmail()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById($user->id);

        $accountValidator = new AccountValidator();

        $email = $accountValidator->checkEmail($post['email']);

        if ($email != $account->email) {
            $accountValidator->checkIfEmailTaken($post['email']);
        }

        $accountValidator->checkOriginPassword($account, $post['origin_password']);

        $securityValidator = new SecurityValidator();

        $securityValidator->checkVerifyCode($post['email'], $post['verify_code']);

        $account->email = $email;

        $account->update();

        return $account;
    }

}
