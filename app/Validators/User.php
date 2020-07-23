<?php

namespace App\Validators;

use App\Caches\MaxUserId as MaxUserIdCache;
use App\Caches\User as UserCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Models\User as UserModel;
use App\Repos\Role as RoleRepo;
use App\Repos\User as UserRepo;
use App\Services\Auth\Admin as AdminAuth;

class User extends Validator
{

    /**
     * @param int $id
     * @return UserModel
     * @throws BadRequestException
     */
    public function checkUserCache($id)
    {
        $id = intval($id);

        $maxUserIdCache = new MaxUserIdCache();

        $maxUserId = $maxUserIdCache->get();

        /**
         * 防止缓存穿透
         */
        if ($id < 1 || $id > $maxUserId) {
            throw new BadRequestException('user.not_found');
        }

        $userCache = new UserCache();

        $user = $userCache->get($id);

        if (!$user) {
            throw new BadRequestException('user.not_found');
        }

        return $user;
    }

    public function checkUser($id)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($id);

        if (!$user) {
            throw new BadRequestException('user.not_found');
        }

        return $user;
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
            throw new BadRequestException('user.title_too_long');
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

    public function checkGender($value)
    {
        $list = UserModel::genderTypes();

        if (!isset($list[$value])) {
            throw new BadRequestException('user.invalid_gender');
        }

        return $value;
    }

    public function checkArea($area)
    {
        if (empty($area['province'] || empty($area['city']) || empty($area['county']))) {
            throw new BadRequestException('user.invalid_area');
        }

        return join('/', $area);
    }

    public function checkEduRole($value)
    {
        $list = UserModel::eduRoleTypes();

        if (!isset($list[$value])) {
            throw new BadRequestException('user.invalid_edu_role');
        }

        return $value;
    }

    public function checkAdminRole($value)
    {
        if (!$value) return 0;

        $roleRepo = new RoleRepo();

        $role = $roleRepo->findById($value);

        if (!$role || $role->deleted == 1) {
            throw new BadRequestException('user.invalid_admin_role');
        }

        return $role->id;
    }

    public function checkVipStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('user.invalid_vip_status');
        }

        return $status;
    }

    public function checkVipExpiryTime($expiryTime)
    {
        if (!CommonValidator::date($expiryTime, 'Y-m-d H:i:s')) {
            throw new BadRequestException('user.invalid_vip_expiry_time');
        }

        return strtotime($expiryTime);
    }

    public function checkLockStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('user.invalid_lock_status');
        }

        return $status;
    }

    public function checkLockExpiryTime($expiryTime)
    {
        if (!CommonValidator::date($expiryTime, 'Y-m-d H:i:s')) {
            throw new BadRequestException('user.invalid_lock_expiry_time');
        }

        return strtotime($expiryTime);
    }

    public function checkIfNameTaken($name)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findByName($name);

        if ($user) {
            throw new BadRequestException('user.name_taken');
        }
    }

    public function checkIfCanEditUser($user)
    {
        /**
         * @var AdminAuth $auth
         */
        $auth = $this->getDI()->get('auth');

        $authUser = $auth->getAuthInfo();

        if ($authUser['id']) {

        }
    }

}
