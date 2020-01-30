<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\Forbidden as ForbiddenException;
use App\Library\Util\Password as PasswordUtil;
use App\Library\Validator\Common as CommonValidator;
use App\Repos\Account as AccountRepo;
use App\Repos\User as UserRepo;

class Account extends Validator
{

    public function checkPhone($phone)
    {
        $value = $this->filter->sanitize($phone, ['trim', 'string']);

        if (!CommonValidator::phone($value)) {
            throw new BadRequestException('account.invalid_phone');
        }

        return $value;
    }

    public function checkEmail($email)
    {
        $value = $this->filter->sanitize($email, ['trim', 'string']);

        if (!CommonValidator::email($value)) {
            throw new BadRequestException('account.invalid_email');
        }

        return $value;
    }

    public function checkPassword($password)
    {
        $value = $this->filter->sanitize($password, ['trim', 'string']);

        if (!CommonValidator::password($value)) {
            throw new BadRequestException('account.invalid_password');
        }

        return $value;
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

    public function checkOriginPassword($user, $password)
    {
        $hash = PasswordUtil::hash($password, $user->salt);

        if ($hash != $user->password) {
            throw new BadRequestException('user.origin_password_incorrect');
        }
    }

    public function checkConfirmPassword($newPassword, $confirmPassword)
    {
        if ($newPassword != $confirmPassword) {
            throw new BadRequestException('user.confirm_password_incorrect');
        }
    }

    public function checkUserLogin($name, $password)
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

        $hash = PasswordUtil::hash($password, $account->salt);

        if ($hash != $account->password) {
            throw new BadRequestException('account.login_password_incorrect');
        }

        $userRepo = new UserRepo();

        $user = $userRepo->findById($account->id);

        return $user;
    }

    public function checkAdminLogin($name, $password)
    {
        $user = $this->checkUserLogin($name, $password);

        if ($user->admin_role == 0) {
            throw new ForbiddenException('account.admin_not_authorized');
        }

        return $user;
    }

}
