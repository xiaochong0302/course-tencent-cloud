<?php

namespace App\Http\Admin\Services;

use App\Models\Role as RoleModel;
use App\Models\User as UserModel;
use Phalcon\Mvc\User\Component as UserComponent;

class AuthUser extends UserComponent
{

    /**
     * 判断权限
     *
     * @param string $route
     * @return bool
     */
    public function hasPermission($route)
    {
        $authUser = $this->getAuthUser();

        if ($authUser->admin) return true;

        if (in_array($route, $authUser->routes)) return true;

        return false;
    }

    /**
     * 写入会话
     *
     * @param UserModel $user
     */
    public function setAuthUser(UserModel $user)
    {
        $role = RoleModel::findFirstById($user->admin_role);

        if ($role->id == RoleModel::ROLE_ADMIN) {
            $admin = 1;
            $routes = [];
        } else {
            $admin = 0;
            $routes = $role->routes;
        }

        $authKey = $this->getAuthKey();

        $authUser = new \stdClass();

        $authUser->id = $user->id;
        $authUser->name = $user->name;
        $authUser->avatar = $user->avatar;
        $authUser->admin = $admin;
        $authUser->routes = $routes;

        $this->session->set($authKey, $authUser);
    }

    /**
     * 清除会话
     */
    public function removeAuthUser()
    {
        $authKey = $this->getAuthKey();

        $this->session->remove($authKey);
    }

    /**
     * 读取会话
     *
     * @return mixed
     */
    public function getAuthUser()
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
        return 'admin';
    }

}
