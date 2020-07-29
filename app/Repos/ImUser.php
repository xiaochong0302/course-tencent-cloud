<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\ImFriendGroup as ImFriendGroupModel;
use App\Models\ImFriendMessage as ImFriendMessageModel;
use App\Models\ImFriendUser as ImFriendUserModel;
use App\Models\ImGroup as ImGroupModel;
use App\Models\ImGroupUser as ImGroupUserModel;
use App\Models\ImSystemMessage as ImSystemMessageModel;
use App\Models\ImUser as ImUserModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class ImUser extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(ImUserModel::class);

        $builder->where('1 = 1');

        if (!empty($where['id'])) {
            $builder->andWhere('id = :id:', ['id' => $where['id']]);
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
     * @return ImUserModel|Model|bool
     */
    public function findById($id)
    {
        return ImUserModel::findFirst($id);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|ImUserModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return ImUserModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param int $userId
     * @return ResultsetInterface|Resultset|ImFriendUserModel[]
     */
    public function findFriendUsers($userId)
    {
        return ImFriendUserModel::query()
            ->where('user_id = :user_id:', ['user_id' => $userId])
            ->orderBy('update_time DESC')
            ->execute();
    }

    /**
     * @param int $userId
     * @return ResultsetInterface|Resultset|ImFriendGroupModel[]
     */
    public function findFriendGroups($userId)
    {
        return ImFriendGroupModel::query()
            ->where('user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('deleted = 0')
            ->execute();
    }

    /**
     * @param int $userId
     * @return ResultsetInterface|Resultset|ImGroupModel[]
     */
    public function findGroups($userId)
    {
        return $this->modelsManager->createBuilder()
            ->columns('g.*')
            ->addFrom(ImGroupModel::class, 'g')
            ->join(ImGroupUserModel::class, 'g.id = gu.group_id', 'gu')
            ->where('gu.user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('g.published = 0')
            ->getQuery()->execute();
    }

    /**
     * @param int $friendId
     * @param int $userId
     * @return ResultsetInterface|Resultset|ImFriendMessageModel[]
     */
    public function findUnreadFriendMessages($friendId, $userId)
    {
        return ImFriendMessageModel::find([
            'conditions' => 'sender_id = ?1 AND receiver_id = ?2 AND viewed = ?3',
            'bind' => [1 => $friendId, 2 => $userId, 3 => 0],
        ]);
    }

    /**
     * @param int $userId
     * @param int $itemType
     * @return ImSystemMessageModel|Model|bool
     */
    public function findSystemMessage($userId, $itemType)
    {
        return ImSystemMessageModel::findFirst([
            'conditions' => 'receiver_id = ?1 AND item_type = ?2',
            'bind' => [1 => $userId, 2 => $itemType],
            'order' => 'id DESC',
        ]);
    }

    /**
     * @param int $userId
     * @return ResultsetInterface|Resultset|ImFriendMessageModel[]
     */
    public function findUnreadSystemMessages($userId)
    {
        return ImSystemMessageModel::find([
            'conditions' => 'receiver_id = ?1 AND viewed = ?2',
            'bind' => [1 => $userId, 2 => 0],
        ]);
    }

    public function countUnreadSystemMessages($userId)
    {
        return (int)ImSystemMessageModel::count([
            'conditions' => 'receiver_id = ?1 AND viewed = ?2',
            'bind' => [1 => $userId, 2 => 0],
        ]);
    }

}
