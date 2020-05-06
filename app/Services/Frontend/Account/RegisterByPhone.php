<?php

namespace App\Services\Frontend\Account;

use App\Models\Account as AccountModel;
use App\Services\Frontend\Service;
use App\Validators\Account as AccountValidator;
use App\Validators\Verify as VerifyValidator;

class RegisterByPhone extends Service
{

    public function handle()
    {
        $post = $this->request->getPost();

        $verifyValidator = new VerifyValidator();

        $verifyValidator->checkSmsCode($post['phone'], $post['verify_code']);

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
