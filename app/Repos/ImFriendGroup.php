<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\ImFriendGroup as ImFriendGroupModel;
use App\Models\ImFriendUser as ImFriendUserModel;
use App\Models\User as UserModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class ImFriendGroup extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(ImFriendGroupModel::class);

        $builder->where('1 = 1');

        if (!empty($where['user_id'])) {
            $builder->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
        }

        if (!empty($where['name'])) {
            $builder->andWhere('name LIKE :name:', ['name' => "%{$where['name']}%"]);
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

    /**
     * @param int $id
     * @return ImFriendGroupModel|Model|bool
     */
    public function findById($id)
    {
        return ImFriendGroupModel::findFirst($id);
    }

    /**
     * @param array $ids
     * @param string|array $columns
     * @return ResultsetInterface|Resultset|ImFriendGroupModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return ImFriendGroupModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param int $groupId
     * @return ResultsetInterface|Resultset|UserModel[]
     */
    public function findUsers($groupId)
    {
        return $this->modelsManager->createBuilder()
            ->columns('u.*')
            ->addFrom(UserModel::class, 'u')
            ->join(ImFriendUserModel::class, 'u.id = fu.user_id', 'fu')
            ->where('fu.group_id = :group_id:', ['group_id' => $groupId])
            ->getQuery()->execute();
    }

    public function countUsers($groupId)
    {
        return (int)ImFriendUserModel::count([
            'conditions' => 'group_id = :group_id: AND blocked = 0',
            'bind' => ['group_id' => $groupId],
        ]);
    }

}
