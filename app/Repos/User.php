<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\User as UserModel;

class User extends Repository
{

    /**
     * @param int $id
     * @return UserModel
     */
    public function findById($id)
    {
        $result = UserModel::findFirst($id);

        return $result;
    }

    /**
     * @param string $name
     * @return UserModel
     */
    public function findByName($name)
    {
        $result = UserModel::findFirst([
            'conditions' => 'name = :name:',
            'bind' => ['name' => $name],
        ]);

        return $result;
    }

    public function findByIds($ids, $columns = '*')
    {
        $result = UserModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

    public function findTeachers()
    {
        $eduRole = UserModel::EDU_ROLE_TEACHER;

        $result = UserModel::query()
            ->where('edu_role = :edu_role:', ['edu_role' => $eduRole])
            ->andWhere('locked = :locked:', ['locked' => 0])
            ->execute();

        return $result;
    }

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(UserModel::class);

        $builder->where('1 = 1');

        if (!empty($where['id'])) {
            $builder->andWhere('id = :id:', ['id' => $where['id']]);
        }

        if (!empty($where['name'])) {
            $builder->andWhere('name LIKE :name:', ['name' => "%{$where['name']}%"]);
        }

        if (!empty($where['edu_role'])) {
            $builder->andWhere('edu_role = :edu_role:', ['edu_role' => $where['edu_role']]);
        }

        if (!empty($where['admin_role'])) {
            $builder->andWhere('admin_role = :admin_role:', ['admin_role' => $where['admin_role']]);
        }

        if (isset($where['vip'])) {
            $builder->andWhere('vip = :vip:', ['vip' => $where['vip']]);
        }

        if (isset($where['locked'])) {
            $builder->andWhere('locked = :locked:', ['locked' => $where['locked']]);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        switch ($sort) {
            default:
                $orderBy = 'id DESC';
                break;
        }

        $builder->orderBy($orderBy);

        $pager = new PagerQueryBuilder([
            'builder' => $builder,
            'page' => $page,
            'limit' => $limit,
        ]);

        return $pager->paginate();
    }

}
