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
use App\Models\Client as ClientModel;
use App\Models\User as UserModel;
use App\Repos\Account as AccountRepo;
use App\Repos\User as UserRepo;
use App\Repos\UserSession as UserSessionRepo;
use App\Repos\UserToken as UserTokenRepo;
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

        if (!$account || $account->deleted == 1) {
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
        $case1 = $user->locked == 1;
        $case2 = $user->lock_expiry_time > time();

        if ($case1 && $case2) {
            throw new ForbiddenException('account.locked');
        }

        $this->checkFloodLogin($user->id);
    }

    public function checkFloodLogin($userId)
    {
        $clientIp = $this->getClientIp();
        $clientType = $this->getClientType();

        if ($clientType == ClientModel::TYPE_PC) {
            $repo = new UserSessionRepo();
            $records = $repo->findUserRecentSessions($userId, 10);
        } else {
            $repo = new UserTokenRepo();
            $records = $repo->findUserRecentTokens($userId, 10);
        }

        if ($records->count() == 0) return;

        $clientIps = array_column($records->toArray(), 'client_ip');

        $countValues = array_count_values($clientIps);

        foreach ($countValues as $ip => $count) {
            if ($clientIp == $ip && $count > 4) {
                throw new ForbiddenException('account.flood_login');
            }
        }
    }

}
