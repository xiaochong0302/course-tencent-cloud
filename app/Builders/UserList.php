<?php

namespace App\Builders;

use App\Models\User as UserModel;
use App\Repos\Role as RoleRepo;

class UserList extends Builder
{

    public function handleUsers($users)
    {
        $imgBaseUrl = kg_img_base_url();

        foreach ($users as $key => $user) {
            $users[$key]['avatar'] = $imgBaseUrl . $user['avatar'];
        }

        return $users;
    }

    public function handleAdminRoles($users)
    {
        $roles = $this->getAdminRoles($users);

        foreach ($users as $key => $user) {
            $users[$key]['admin_role'] = $roles[$user['admin_role']] ?? ['id' => 0, 'name' => 'N/A'];
        }

        return $users;
    }

    public function handleEduRoles($users)
    {
        $roles = $this->getEduRoles();

        foreach ($users as $key => $user) {
            $users[$key]['edu_role'] = $roles[$user['edu_role']] ?? ['id' => 0, 'name' => 'N/A'];
        }

        return $users;
    }

    protected function getAdminRoles($users)
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

    protected function getEduRoles()
    {
        $result = [
            UserModel::EDU_ROLE_STUDENT => [
                'id' => UserModel::EDU_ROLE_STUDENT,
                'name' => '学员',
            ],
            UserModel::EDU_ROLE_TEACHER => [
                'id' => UserModel::EDU_ROLE_TEACHER,
                'name' => '讲师',
            ],
        ];

        return $result;
    }

}
