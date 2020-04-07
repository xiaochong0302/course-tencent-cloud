<?php

namespace App\Services\Auth;

use App\Models\Role as RoleModel;
use App\Models\User as UserModel;
use App\Repos\Role as RoleRepo;
use App\Services\Auth as AuthService;
use Yansongda\Supports\Collection;

class Admin extends AuthService
{

    public function saveAuthInfo(UserModel $user)
    {
        $roleRepo = new RoleRepo();

        $role = $roleRepo->findById($user->admin_role);

        $root = $role->id == RoleModel::ROLE_ROOT ? 1 : 0;

        $authInfo = [
            'id' => $user->id,
            'name' => $user->name,
            'routes' => $role->routes,
            'root' => $root,
        ];

        $authKey = $this->getAuthKey();

        $this->session->set($authKey, $authInfo);
    }

    public function clearAuthInfo()
    {
        $authKey = $this->getAuthKey();

        $this->session->remove($authKey);
    }

    public function getAuthInfo()
    {
        $authKey = $this->getAuthKey();

        $authInfo = $this->session->get($authKey);

        $items = $authInfo ? $authInfo : [];

        return new Collection($items);
    }

    public function getAuthKey()
    {
        return 'admin_auth_info';
    }

    public function hasPermission($route)
    {
        $authUser = $this->getAuthInfo();

        if ($authUser['root']) {
            return true;
        }

        if (in_array($route, $authUser['routes'])) {
            return true;
        }

        return false;
    }

}
