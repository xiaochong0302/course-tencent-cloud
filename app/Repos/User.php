<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\CourseUser as CourseUserModel;
use App\Models\User as UserModel;
use App\Models\UserBalance as UserBalanceModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\Model\Row;
use Phalcon\Paginator\RepositoryInterface as PagerRepoInterface;

class User extends Repository
{

    /**
     * @param array $where
     * @param string $sort
     * @param int $page
     * @param int $limit
     * @return PagerRepoInterface
     */
    public function paginate(array $where = [], string $sort = 'latest', int $page = 1, int $limit = 15): PagerRepoInterface
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
            if (is_array($where['edu_role'])) {
                $builder->inWhere('edu_role', $where['edu_role']);
            } else {
                $builder->andWhere('edu_role = :edu_role:', ['edu_role' => $where['edu_role']]);
            }
        }

        if (!empty($where['admin_role'])) {
            if (is_array($where['admin_role'])) {
                $builder->inWhere('admin_role', $where['admin_role']);
            } else {
                $builder->andWhere('admin_role = :admin_role:', ['admin_role' => $where['admin_role']]);
            }
        }

        if (!empty($where['create_time'][0]) && !empty($where['create_time'][1])) {
            $startTime = strtotime($where['create_time'][0]);
            $endTime = strtotime($where['create_time'][1]);
            $builder->betweenWhere('create_time', $startTime, $endTime);
        }

        if (!empty($where['active_time'][0]) && !empty($where['active_time'][1])) {
            $startTime = strtotime($where['active_time'][0]);
            $endTime = strtotime($where['active_time'][1]);
            $builder->betweenWhere('active_time', $startTime, $endTime);
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

        $orderBy = match ($sort) {
            'oldest' => 'id ASC',
            default => 'id DESC',
        };

        $builder->orderBy($orderBy);

        $pager = new PagerQueryBuilder([
            'builder' => $builder,
            'page' => $page,
            'limit' => $limit,
        ]);

        return $pager->paginate();
    }

    /**
     * @param int $id
     * @return UserModel|Row|null
     */
    public function findById(int $id)
    {
        return UserModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param string $name
     * @return UserModel|Row|null
     */
    public function findByName(string $name)
    {
        return UserModel::findFirst([
            'conditions' => 'name = :name:',
            'bind' => ['name' => $name],
        ]);
    }

    /**
     * @return UserModel|Row|null
     */
    public function findByRand()
    {
        return UserModel::query()
            ->orderBy('RAND()')
            ->limit(1)
            ->execute()
            ->getFirst();
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|UserModel[]
     */
    public function findByIds(array $ids, array|string $columns = '*')
    {
        return UserModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param array $ids
     * @return ResultsetInterface|Resultset|UserModel[]
     */
    public function findShallowUserByIds(array $ids)
    {
        return UserModel::query()
            ->columns(['id', 'name', 'avatar', 'vip', 'title', 'about'])
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param int $userId
     * @return UserBalanceModel|Row|null
     */
    public function findUserBalance(int $userId)
    {
        return UserBalanceModel::findFirst([
            'conditions' => 'user_id = :user_id:',
            'bind' => ['user_id' => $userId],
        ]);
    }

    /**
     * @return ResultsetInterface|Resultset|UserModel[]
     */
    public function findTeachers()
    {
        $eduRole = UserModel::EDU_ROLE_TEACHER;

        return UserModel::query()
            ->where('edu_role = :edu_role:', ['edu_role' => $eduRole])
            ->andWhere('deleted = 0')
            ->execute();
    }

    /**
     * @return int
     */
    public function countUsers(): int
    {
        return (int)UserModel::count([
            'conditions' => 'deleted = 0',
        ]);
    }

    /**
     * @return int
     */
    public function countVipUsers(): int
    {
        return (int)UserModel::count([
            'conditions' => 'vip = 1 AND deleted = 0',
        ]);
    }

    /**
     * @param int $userId
     * @return int
     */
    public function countStudyCourses(int $userId): int
    {
        return (int)CourseUserModel::count([
            'conditions' => 'user_id = :user_id: AND deleted = 0',
            'bind' => ['user_id' => $userId],
        ]);
    }

}
