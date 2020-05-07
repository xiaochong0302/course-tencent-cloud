<?php

namespace App\Services\Frontend\Account;

use App\Library\Validators\Common as CommonValidator;
use App\Models\Account as AccountModel;
use App\Repos\User as UserRepo;
use App\Services\Frontend\Service;
use App\Validators\Account as AccountValidator;
use App\Validators\Verify as VerifyValidator;

class Register extends Service
{

    public function handle()
    {
        $post = $this->request->getPost();

        $verifyValidator = new VerifyValidator();

        $verifyValidator->checkCode($post['account'], $post['verify_code']);

        $accountValidator = new AccountValidator();

        $data = [];

        if (CommonValidator::email($post['account'])) {

            $data['email'] = $accountValidator->checkEmail($post['account']);

            $accountValidator->checkIfEmailTaken($post['account']);

        } elseif (CommonValidator::phone($post['account'])) {

            $data['phone'] = $accountValidator->checkPhone($post['account']);

            $accountValidator->checkIfPhoneTaken($post['account']);
        }

        $data['password'] = $accountValidator->checkPassword($post['password']);

        $account = new AccountModel();

        $account->create($data);

        $userRepo = new UserRepo();

        return $userRepo->findById($account->id);
    }

}
