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

    public function checkAccount($name)
    {
        $account = null;

        $accountRepo = new AccountRepo();

        if (CommonValidator::email($name)) {
            $account = $accountRepo->findByEmail($name);
        } elseif (CommonValidator::phone($name)) {
            $account = $accountRepo->findByPhone($name);
        } else {
            $account = $accountRepo->findById($name);
        }

        if (!$account) {
            throw new BadRequestException('account.not_found');
        }

        return $account;
    }

    public function checkLoginName($name)
    {
        $isPhone = CommonValidator::phone($name);
        $isEmail = CommonValidator::email($name);

        $loginNameOk = $isPhone || $isEmail;

        if (!$loginNameOk) {
            throw new BadRequestException('account.invalid_login_name');
        }
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
            throw new BadRequestException('account.invalid_pwd');
        }

        return $password;
    }

    public function checkConfirmPassword($newPassword, $confirmPassword)
    {
        if ($newPassword != $confirmPassword) {
            throw new BadRequestException('account.pwd_not_match');
        }
    }

    public function checkOriginPassword(AccountModel $account, $password)
    {
        $hash = PasswordUtil::hash($password, $account->salt);

        if ($hash != $account->password) {
            throw new BadRequestException('account.origin_pwd_incorrect');
        }
    }

    public function checkLoginPassword(AccountModel $account, $password)
    {
        $hash = PasswordUtil::hash($password, $account->salt);

        if ($hash != $account->password) {
            throw new BadRequestException('account.login_pwd_incorrect');
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

    public function checkVerifyLogin($name, $code)
    {
        $account = $this->checkAccount($name);

        $verify = new Verify();

        $verify->checkCode($name, $code);

        $userRepo = new UserRepo();

        return $userRepo->findById($account->id);
    }

    public function checkUserLogin($name, $password)
    {
        $account = $this->checkAccount($name);

        $hash = PasswordUtil::hash($password, $account->salt);

        if ($hash != $account->password) {
            throw new BadRequestException('account.login_pwd_incorrect');
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
