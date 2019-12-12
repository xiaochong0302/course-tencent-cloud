<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\Forbidden as ForbiddenException;
use App\Exceptions\NotFound as NotFoundException;
use App\Library\Util\Password as PasswordUtil;
use App\Library\Util\Verification as VerificationUtil;
use App\Library\Validator\Common as CommonValidator;
use App\Models\User as UserModel;
use App\Repos\Role as RoleRepo;
use App\Repos\User as UserRepo;
use App\Services\Captcha as CaptchaService;

class User extends Validator
{
    /**
     * @param integer $id
     * @return \App\Models\User
     * @throws NotFoundException
     */
    public function checkUser($id)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($id);

        if (!$user) {
            throw new NotFoundException('user.not_found');
        }

        return $user;
    }

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

    public function checkName($name)
    {
        $value = $this->filter->sanitize($name, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('user.name_too_short');
        }

        if ($length > 15) {
            throw new BadRequestException('user.name_too_long');
        }

        return $value;
    }

    public function checkTitle($title)
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 30) {
            throw new BadRequestException('role.title_too_long');
        }

        return $value;
    }

    public function checkAbout($about)
    {
        $value = $this->filter->sanitize($about, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 255) {
            throw new BadRequestException('user.about_too_long');
        }

        return $value;
    }

    public function checkEduRole($role)
    {
        $value = $this->filter->sanitize($role, ['trim', 'int']);

        $roleIds = [UserModel::EDU_ROLE_STUDENT, UserModel::EDU_ROLE_TEACHER];

        if (!in_array($value, $roleIds)) {
            throw new BadRequestException('user.invalid_edu_role');
        }

        return $value;
    }

    public function checkAdminRole($role)
    {
        $value = $this->filter->sanitize($role, ['trim', 'int']);

        if (!$value) return 0;

        $roleRepo = new RoleRepo();

        $role = $roleRepo->findById($value);

        if (!$role || $role->deleted == 1) {
            throw new BadRequestException('user.invalid_admin_role');
        }

        return $role->id;
    }

    public function checkLockStatus($status)
    {
        $value = $this->filter->sanitize($status, ['trim', 'int']);

        if (!in_array($value, [0, 1])) {
            throw new BadRequestException('user.invalid_lock_status');
        }

        return $value;
    }

    public function checkLockExpiry($expiry)
    {
        if (!CommonValidator::date($expiry, 'Y-m-d H:i:s')) {
            throw new BadRequestException('user.invalid_locked_expiry');
        }

        return strtotime($expiry);
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

    public function checkIfNameTaken($name)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findByName($name);

        if ($user) {
            throw new BadRequestException('user.name_taken');
        }
    }

    public function checkVerifyCode($key, $code)
    {
        if (!VerificationUtil::checkCode($key, $code)) {
            throw new BadRequestException('user.invalid_verify_code');
        }
    }

    public function checkCaptchaCode($ticket, $rand)
    {
        $captchaService = new CaptchaService();

        $result = $captchaService->verify($ticket, $rand);

        if (!$result) {
            throw new BadRequestException('user.invalid_captcha_code');
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

    public function checkAdminLogin($user)
    {
        if ($user->admin_role == 0) {
            throw new ForbiddenException('user.admin_not_authorized');
        }
    }

    public function checkLoginAccount($account)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findByAccount($account);

        if (!$user) {
            throw new BadRequestException('user.login_account_incorrect');
        }

        return $user;
    }

    public function checkLoginPassword($user, $password)
    {
        $hash = PasswordUtil::hash($password, $user->salt);

        if ($hash != $user->password) {
            throw new BadRequestException('user.login_password_incorrect');
        }

        if ($user->locked == 1) {
            throw new ForbiddenException('user.login_locked');
        }

    }

    public function checkIfCanEditUser($user)
    {
        $auth = $this->getDI()->get('auth');

        $authUser = $auth->getAuthUser();

        if ($authUser->id) {
        }
    }

}
