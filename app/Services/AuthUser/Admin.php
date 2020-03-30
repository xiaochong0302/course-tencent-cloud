<?php

namespace App\Services\AuthUser;

use App\Models\Role as RoleModel;
use App\Models\User as UserModel;
use App\Repos\Role as RoleRepo;
use App\Services\AuthUser;

class Admin extends AuthUser
{

    /**
     * 写入会话
     *
     * @param UserModel $user
     */
    public function saveAuthInfo(UserModel $user)
    {
        $roleRepo = new RoleRepo();

        $role = $roleRepo->findById($user->admin_role);

        $root = $role->id == RoleModel::ROLE_ROOT ? 1 : 0;

        $authUser = new \stdClass();

        $authUser->id = $user->id;
        $authUser->name = $user->name;
        $authUser->avatar = $user->avatar;
        $authUser->routes = $role->routes;
        $authUser->root = $root;

        $authKey = $this->getAuthKey();

        $this->session->set($authKey, $authUser);
    }

    /**
     * 清除会话
     */
    public function clearAuthInfo()
    {
        $authKey = $this->getAuthKey();

        $this->session->remove($authKey);
    }

    /**
     * 读取会话
     *
     * @return mixed
     */
    public function getAuthInfo()
    {
        $authKey = $this->getAuthKey();

        return $this->session->get($authKey);
    }

    /**
     * 获取会话键值
     *
     * @return string
     */
    public function getAuthKey()
    {
        return 'admin_info';
    }

    /**
     * 判断权限
     *
     * @param string $route
     * @return bool
     */
    public function hasPermission($route)
    {
        $authUser = $this->getAuthInfo();

        if ($authUser->root) {
            return true;
        }

        if (in_array($route, $authUser->routes)) {
            return true;
        }

        return false;
    }

}
