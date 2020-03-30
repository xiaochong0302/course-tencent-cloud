<?php

namespace App\Services\Frontend\Account;

use App\Models\Account as AccountModel;
use App\Services\Frontend\Service;
use App\Validators\Account as AccountValidator;
use App\Validators\Security as SecurityValidator;

class Register extends Service
{

    public function register()
    {
        $post = $this->request->getPost();

        $securityValidator = new SecurityValidator();

        $securityValidator->checkVerifyCode($post['phone'], $post['verify_code']);

        $accountValidator = new AccountValidator();

        $data = [];

        $data['phone'] = $accountValidator->checkPhone($post['phone']);
        $data['password'] = $accountValidator->checkPassword($post['password']);

        $accountValidator->checkIfPhoneTaken($post['phone']);

        $account = new AccountModel();

        $account->create($data);

        return $account;
    }

}
