<?php

namespace App\Services\Frontend;

use App\Models\Account as AccountModel;
use App\Repos\Account as AccountRepo;
use App\Validators\Account as AccountValidator;
use App\Validators\Security as SecurityValidator;

class Account extends Service
{

    public function createAccount()
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

    public function updatePhone()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById($user->id);

        $accountValidator = new AccountValidator();

        $phone = $accountValidator->checkPhone($post['phone']);

        if ($phone != $account->phone) {
            $accountValidator->checkIfPhoneTaken($post['phone']);
        }

        $accountValidator->checkOriginPassword($account, $post['origin_password']);

        $securityValidator = new SecurityValidator();

        $securityValidator->checkVerifyCode($post['phone'], $post['verify_code']);

        $account->phone = $phone;

        $account->update();

        return $account;
    }

    public function updatePassword()
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

    public function resetPassword()
    {
        $post = $this->request->getPost();

        $accountValidator = new AccountValidator();

        $account = $accountValidator->checkLoginAccount($post['account']);

        $accountValidator->checkPassword($post['new_password']);

        $securityValidator = new SecurityValidator();

        $securityValidator->checkVerifyCode($post['account'], $post['verify_code']);

        $account->password = $post['new_password'];

        $account->update();

        return $account;
    }

}
