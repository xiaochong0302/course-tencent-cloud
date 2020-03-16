<?php

namespace App\Repos;

use App\Models\Role as RoleModel;
use App\Models\User as UserModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Role extends Repository
{

    /**
     * @param int $id
     * @return RoleModel|Model|bool
     */
    public function findById($id)
    {
        $result = RoleModel::findFirst($id);

        return $result;
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|RoleModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        $result = RoleModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

    /**
     * @param array $where
     * @return ResultsetInterface|Resultset|RoleModel[]
     */
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

        return $count;
    }

}
