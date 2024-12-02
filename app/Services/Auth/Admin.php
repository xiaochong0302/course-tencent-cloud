<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Auth;

use App\Models\Role as RoleModel;
use App\Models\User as UserModel;
use App\Repos\Role as RoleRepo;
use App\Services\Auth as AuthService;

class Admin extends AuthService
{

    public function saveAuthInfo(UserModel $user)
    {
        $roleRepo = new RoleRepo();

        $role = $roleRepo->findById($user->admin_role);

        $root = $role->id == RoleModel::ROLE_ROOT ? 1 : 0;

        $authKey = $this->getAuthKey();

        $authInfo = [
            'id' => $user->id,
            'name' => $user->name,
            'routes' => $role->routes,
            'root' => $root,
        ];

        $this->session->set($authKey, $authInfo);

        return $authInfo;
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

        return $authInfo ?: null;
    }

    public function getAuthKey()
    {
        return 'admin_auth_info';
    }

}
