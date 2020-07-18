<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\ImGroup as ImGroupModel;
use App\Models\ImGroupUser as ImGroupUserModel;
use App\Models\ImUser as ImUserModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class ImGroup extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(ImGroupModel::class);

        $builder->where('1 = 1');

        if (!empty($where['course_id'])) {
            $builder->andWhere('sender_id = :sender_id:', ['sender_id' => $where['sender_id']]);
        }

        if (!empty($where['name'])) {
            $builder->andWhere('name LIKE :name:', ['name' => "%{$where['name']}%"]);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        switch ($sort) {
            case 'popular':
                $orderBy = 'user_count DESC';
                break;
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
     * @return ImGroupModel|Model|bool
     */
    public function findById($id)
    {
        return ImGroupModel::findFirst($id);
    }

    /**
     * @param array $ids
     * @param string|array $columns
     * @return ResultsetInterface|Resultset|ImGroupModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return ImGroupModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param int $groupId
     * @return ResultsetInterface|Resultset|ImUserModel[]
     */
    public function findUsers($groupId)
    {
        return $this->modelsManager->createBuilder()
            ->columns('u.*')
            ->addFrom(ImUserModel::class, 'u')
            ->join(ImGroupUserModel::class, 'u.id = gu.user_id', 'gu')
            ->where('gu.group_id = :group_id:', ['group_id' => $groupId])
            ->getQuery()->execute();
    }

    public function countGroups()
    {
        return (int)ImGroupModel::count(['conditions' => 'deleted = 0']);
    }

    public function countUsers($groupId)
    {
        return (int)ImGroupUserModel::count([
            'conditions' => 'group_id = :group_id: AND blocked = 0',
            'bind' => ['group_id' => $groupId],
        ]);
    }

}
