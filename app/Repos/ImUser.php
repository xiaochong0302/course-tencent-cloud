<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\ImFriendGroup as ImFriendGroupModel;
use App\Models\ImFriendUser as ImFriendUserModel;
use App\Models\ImGroup as ImGroupModel;
use App\Models\ImGroupUser as ImGroupUserModel;
use App\Models\ImMessage as ImMessageModel;
use App\Models\ImNotice as ImNoticeModel;
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
        return ImUserModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
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
     * @return ResultsetInterface|Resultset|ImGroupUserModel[]
     */
    public function findGroupUsers($userId)
    {
        return ImGroupUserModel::query()
            ->where('user_id = :user_id:', ['user_id' => $userId])
            ->orderBy('update_time DESC')
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
            ->getQuery()->execute();
    }

    /**
     * @param int $friendId
     * @param int $userId
     * @return ResultsetInterface|Resultset|ImMessageModel[]
     */
    public function findUnreadFriendMessages($friendId, $userId)
    {
        return ImMessageModel::find([
            'conditions' => 'sender_id = ?1 AND receiver_id = ?2 AND viewed = 0',
            'bind' => [1 => $friendId, 2 => $userId],
        ]);
    }

    /**
     * @param int $userId
     * @param int $itemType
     * @return ImNoticeModel|Model|bool
     */
    public function findNotice($userId, $itemType)
    {
        return ImNoticeModel::findFirst([
            'conditions' => 'receiver_id = ?1 AND item_type = ?2',
            'bind' => [1 => $userId, 2 => $itemType],
            'order' => 'id DESC',
        ]);
    }

    /**
     * @param int $userId
     * @return ResultsetInterface|Resultset|ImNoticeModel[]
     */
    public function findUnreadNotices($userId)
    {
        return ImNoticeModel::find([
            'conditions' => 'receiver_id = :receiver_id: AND viewed = 0',
            'bind' => ['receiver_id' => $userId],
        ]);
    }

    public function countUnreadNotices($userId)
    {
        return (int)ImNoticeModel::count([
            'conditions' => 'receiver_id = :receiver_id: AND viewed = 0',
            'bind' => ['receiver_id' => $userId],
        ]);
    }

    public function countFriends($userId)
    {
        return (int)ImFriendUserModel::count([
            'conditions' => 'user_id = :user_id:',
            'bind' => ['user_id' => $userId],
        ]);
    }

    public function countGroups($userId)
    {
        return (int)ImGroupUserModel::count([
            'conditions' => 'user_id = :user_id:',
            'bind' => ['user_id' => $userId],
        ]);
    }

}
