<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\Forbidden as ForbiddenException;
use App\Library\Utils\Password as PasswordUtil;
use App\Library\Validators\Common as CommonValidator;
use App\Models\Account as AccountModel;
use App\Models\User as UserModel;
use App\Repos\Account as AccountRepo;
use App\Repos\User as UserRepo;
use App\Traits\Client as ClientTrait;

class Account extends Validator
{

    use ClientTrait;

    public function checkAccount($name)
    {
        $account = null;

        $accountRepo = new AccountRepo();

        if (CommonValidator::email($name)) {
            $account = $accountRepo->findByEmail($name);
        } elseif (CommonValidator::phone($name)) {
            $account = $accountRepo->findByPhone($name);
        } elseif (CommonValidator::intNumber($name)) {
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

    public function checkRegisterStatus($account)
    {
        $local = $this->getSettings('oauth.local');

        $allowPhone = $local['register_with_phone'] ?? false;
        $allowEmail = $local['register_with_email'] ?? false;

        $isEmail = CommonValidator::email($account);
        $isPhone = CommonValidator::Phone($account);

        if (!$allowPhone && !$allowEmail) {
            throw new BadRequestException('account.register_disabled');
        }

        if ($isPhone && !$allowPhone) {
            throw new BadRequestException('account.register_with_phone_disabled');
        }

        if ($isEmail && !$allowEmail) {
            throw new BadRequestException('account.register_with_email_disabled');
        }
    }

    public function checkVerifyLogin($name, $code)
    {
        $this->checkLoginName($name);

        $account = $this->checkAccount($name);

        $verify = new Verify();

        $verify->checkCode($name, $code);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($account->id);

        if ($user->deleted == 1) {
            throw new BadRequestException('user.not_found');
        }

        return $user;
    }

    public function checkUserLogin($name, $password)
    {
        $this->checkLoginName($name);

        $account = $this->checkAccount($name);

        if (!PasswordUtil::checkHash($password, $account->salt, $account->password)) {
            throw new BadRequestException('account.login_pwd_incorrect');
        }

        $userRepo = new UserRepo();

        $user = $userRepo->findById($account->id);

        if ($user->deleted == 1) {
            throw new BadRequestException('user.not_found');
        }

        return $user;
    }

    public function checkAdminLogin($name, $password)
    {
        $user = $this->checkUserLogin($name, $password);

        if ($user->admin_role == 0) {
            throw new ForbiddenException('sys.forbidden');
        }

        return $user;
    }

    public function checkIfAllowLogin(UserModel $user)
    {
        $locked = false;

        if ($user->locked == 1) {
            if ($user->lock_expiry_time == 0) {
                $locked = true;
            } elseif ($user->lock_expiry_time > time()) {
                $locked = true;
            }
        }

        if ($locked) {
            throw new BadRequestException('account.locked');
        }
    }

}
