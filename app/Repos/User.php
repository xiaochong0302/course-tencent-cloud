<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Library\Validator\Common as CommonValidator;
use App\Models\User as UserModel;

class User extends Repository
{

    /**
     * @param integer $id
     * @return UserModel
     */
    public function findById($id)
    {
        $result = UserModel::findFirstById($id);

        return $result;
    }

    /**
     * @param string $phone
     * @return UserModel
     */
    public function findByPhone($phone)
    {
        $result = UserModel::findFirstByPhone($phone);

        return $result;
    }

    /**
     * @param string $email
     * @return UserModel
     */
    public function findByEmail($email)
    {
        $result = UserModel::findFirstByEmail($email);

        return $result;
    }

    /**
     * @param string $name
     * @return UserModel
     */
    public function findByName($name)
    {
        $result = UserModel::findFirstByName($name);

        return $result;
    }

    /**
     * @param string $account
     * @return UserModel
     */
    public function findByAccount($account)
    {
        if (CommonValidator::email($account)) {
            $user = $this->findByEmail($account);
        } elseif (CommonValidator::phone($account)) {
            $user = $this->findByPhone($account);
        } else {
            $user = $this->findByName($account);
        }

        return $user;
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
            $builder->andWhere('name = :name:', ['name' => $where['name']]);
        }

        if (!empty($where['email'])) {
            $builder->andWhere('email = :email:', ['email' => $where['email']]);
        }

        if (!empty($where['phone'])) {
            $builder->andWhere('phone = :phone:', ['phone' => $where['phone']]);
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

        return $pager->getPaginate();
    }

}
