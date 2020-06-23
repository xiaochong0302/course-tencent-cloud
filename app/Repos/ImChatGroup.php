<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\ImChatGroup as ImChatGroupModel;
use App\Models\ImChatGroupUser as ImGroupUserModel;
use App\Models\User as UserModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class ImChatGroup extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(ImChatGroupModel::class);

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
     * @return ImChatGroupModel|Model|bool
     */
    public function findById($id)
    {
        return ImChatGroupModel::findFirst($id);
    }

    /**
     * @param array $ids
     * @param string|array $columns
     * @return ResultsetInterface|Resultset|ImChatGroupModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return ImChatGroupModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param int $groupId
     * @return ResultsetInterface|Resultset|UserModel[]
     */
    public function findGroupUsers($groupId)
    {
        return $this->modelsManager->createBuilder()
            ->columns('u.*')
            ->addFrom(UserModel::class, 'u')
            ->join(ImGroupUserModel::class, 'u.id = gu.user_id', 'gu')
            ->where('gu.group_id = :group_id:', ['group_id' => $groupId])
            ->andWhere('u.deleted = 0')
            ->getQuery()->execute();
    }

}
