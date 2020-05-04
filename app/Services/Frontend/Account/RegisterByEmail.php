<?php

namespace App\Services\Frontend\Account;

use App\Models\Account as AccountModel;
use App\Services\Frontend\Service;
use App\Validators\Account as AccountValidator;
use App\Validators\Security as SecurityValidator;

class RegisterByEmail extends Service
{

    public function handle()
    {
        $post = $this->request->getPost();

        $securityValidator = new SecurityValidator();

        $securityValidator->checkVerifyCode($post['email'], $post['verify_code']);

        $accountValidator = new AccountValidator();

        $data = [];

        $data['email'] = $accountValidator->checkEmail($post['email']);
        $data['password'] = $accountValidator->checkPassword($post['password']);

        $accountValidator->checkIfEmailTaken($post['email']);

        $account = new AccountModel();

        $account->create($data);

        return $account;
    }

}
