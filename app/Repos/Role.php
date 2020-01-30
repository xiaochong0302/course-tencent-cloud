<?php

namespace App\Repos;

use App\Models\Role as RoleModel;
use App\Models\User as UserModel;

class Role extends Repository
{

    /**
     * @param int $id
     * @return RoleModel
     */
    public function findById($id)
    {
        $result = RoleModel::findFirst($id);

        return $result;
    }

    public function findByIds($ids, $columns = '*')
    {
        $result = RoleModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

    public function findAll($where = [])
    {
        $query = RoleModel::query();

        $query->where('1 = 1');

        if (isset($where['deleted'])) {
            $query->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        $result = $query->execute();

        return $result;
    }

    public function countUsers($roleId)
    {
        $count = UserModel::count([
            'conditions' => 'admin_role = :role_id:',
            'bind' => ['role_id' => $roleId],
        ]);

        return (int)$count;
    }
}
