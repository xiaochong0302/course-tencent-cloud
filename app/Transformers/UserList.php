<?php

namespace App\Transformers;

use App\Models\User as UserModel;
use App\Repos\Role as RoleRepo;

class UserList extends Transformer
{

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
        $roles = $this->getEduRoles($users);

        foreach ($users as $key => $user) {
            $users[$key]['edu_role'] = $roles[$user['edu_role']] ?? ['id' => 0, 'name' => 'N/A'];
        }

        return $users;
    }

    private function getAdminRoles($users)
    {
        $ids = kg_array_column($users, 'admin_role');

        $roleRepo = new RoleRepo();

        $roles = $roleRepo->findByIds($ids, ['id', 'name'])->toArray();

        $result = [];

        foreach ($roles as $role) {
            $result[$role['id']] = $role;
        }

        return $result;
    }

    private function getEduRoles()
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
