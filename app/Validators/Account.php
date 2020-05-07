<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\Forbidden as ForbiddenException;
use App\Library\Utils\Password as PasswordUtil;
use App\Library\Validators\Common as CommonValidator;
use App\Models\Account as AccountModel;
use App\Repos\Account as AccountRepo;
use App\Repos\User as UserRepo;

class Account extends Validator
{

    public function checkAccount($id)
    {
        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById($id);

        if (!$account) {
            throw new BadRequestException('account.not_found');
        }

        return $account;
    }

    public function checkPhone($phone)
    {
        if (!CommonValidator::phone($phone)) {
            throw new BadRequestException('account.invalid_phone');
        }

        return $phone;
    }

    public function checkEmail($email)
    {
        if (!CommonValidator::email($email)) {
            throw new BadRequestException('account.invalid_email');
        }

        return $email;
    }

    public function checkPassword($password)
    {
        if (!CommonValidator::password($password)) {
            throw new BadRequestException('account.invalid_password');
        }

        return $password;
    }

    public function checkOriginPassword(AccountModel $account, $password)
    {
        $hash = PasswordUtil::hash($password, $account->salt);

        if ($hash != $account->password) {
            throw new BadRequestException('account.origin_password_incorrect');
        }
    }

    public function checkIfPhoneTaken($phone)
    {
        $accountRepo = new AccountRepo();

        $account = $accountRepo->findByPhone($phone);

        if ($account) {
            throw new BadRequestException('account.phone_taken');
        }
    }

    public function checkIfEmailTaken($email)
    {
        $accountRepo = new AccountRepo();

        $account = $accountRepo->findByEmail($email);

        if ($account) {
            throw new BadRequestException('account.email_taken');
        }
    }

    public function checkLoginName($name)
    {
        $accountRepo = new AccountRepo();

        $account = null;

        if (CommonValidator::email($name)) {
            $account = $accountRepo->findByEmail($name);
        } elseif (CommonValidator::phone($name)) {
            $account = $accountRepo->findByPhone($name);
        }

        if (!$account) {
            throw new BadRequestException('account.login_name_incorrect');
        }

        return $account;
    }

    public function checkVerifyLogin($name, $code)
    {
        $verify = new Verify();

        $verify->checkCode($name, $code);

        $account = $this->checkLoginName($name);

        $userRepo = new UserRepo();

        return $userRepo->findById($account->id);
    }

    public function checkUserLogin($name, $password)
    {
        $account = $this->checkLoginName($name);

        $hash = PasswordUtil::hash($password, $account->salt);

        if ($hash != $account->password) {
            throw new BadRequestException('account.login_password_incorrect');
        }

        $userRepo = new UserRepo();

        return $userRepo->findById($account->id);
    }

    public function checkAdminLogin($name, $password)
    {
        $user = $this->checkUserLogin($name, $password);

        if ($user->admin_role == 0) {
            throw new ForbiddenException('sys.forbidden');
        }

        return $user;
    }

}
