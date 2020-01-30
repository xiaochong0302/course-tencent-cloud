<?php

namespace App\Validators;

use App\Caches\MaxUserId as MaxUserIdCache;
use App\Caches\User as UserCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validator\Common as CommonValidator;
use App\Models\User as UserModel;
use App\Repos\Role as RoleRepo;
use App\Repos\User as UserRepo;

class User extends Validator
{

    /**
     * @param int $id
     * @return \App\Models\User
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

    /**
     * @param int $id
     * @return \App\Models\User
     * @throws BadRequestException
     */
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
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('user.invalid_lock_status');
        }

        return $status;
    }

    public function checkLockExpiry($expiry)
    {
        if (!CommonValidator::date($expiry, 'Y-m-d H:i:s')) {
            throw new BadRequestException('user.invalid_lock_expiry');
        }

        return strtotime($expiry);
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
        $auth = $this->getDI()->get('auth');

        $authUser = $auth->getAuthInfo();

        if ($authUser->id) {
        }
    }

}
