<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use App\Models\User as UserModel;
use App\Repos\Role as RoleRepo;

class UserList extends Builder
{

    public function handleUsers(array $users): array
    {
        $baseUrl = kg_cos_url();

        foreach ($users as $key => $user) {
            $users[$key]['avatar'] = $baseUrl . $user['avatar'];
        }

        return $users;
    }

    public function handleAccounts(array $users): array
    {
        $accounts = $this->getAccounts($users);

        foreach ($users as $key => $user) {
            $users[$key]['account'] = $accounts[$user['id']] ?? null;
        }

        return $users;
    }

    public function handleAdminRoles(array $users): array
    {
        $roles = $this->getAdminRoles($users);

        foreach ($users as $key => $user) {
            $users[$key]['admin_role'] = $roles[$user['admin_role']] ?? ['id' => 0, 'name' => 'N/A'];
        }

        return $users;
    }

    public function handleEduRoles(array $users): array
    {
        $roles = $this->getEduRoles();

        foreach ($users as $key => $user) {
            $users[$key]['edu_role'] = $roles[$user['edu_role']] ?? ['id' => 0, 'name' => 'N/A'];
        }

        return $users;
    }

    protected function getAccounts(array $users): array
    {
        $ids = kg_array_column($users, 'id');

        return $this->getShallowAccountByIds($ids);
    }

    protected function getAdminRoles(array $users): array
    {
        $ids = kg_array_column($users, 'admin_role');

        $roleRepo = new RoleRepo();

        $roles = $roleRepo->findByIds($ids, ['id', 'name']);

        $result = [];

        foreach ($roles->toArray() as $role) {
            $result[$role['id']] = $role;
        }

        return $result;
    }

    protected function getEduRoles(): array
    {
        return [
            UserModel::EDU_ROLE_STUDENT => [
                'id' => UserModel::EDU_ROLE_STUDENT,
                'name' => '学员',
            ],
            UserModel::EDU_ROLE_TEACHER => [
                'id' => UserModel::EDU_ROLE_TEACHER,
                'name' => '讲师',
            ],
        ];
    }

}
