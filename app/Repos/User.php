<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\ImFriendGroup as ImFriendGroupModel;
use App\Models\ImFriendMessage as ImFriendMessageModel;
use App\Models\ImFriendUser as ImFriendUserModel;
use App\Models\ImGroup as ImGroupModel;
use App\Models\ImGroupUser as ImGroupUserModel;
use App\Models\ImSystemMessage as ImSystemMessageModel;
use App\Models\User as UserModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class User extends Repository
{

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

    /**
     * @param int $id
     * @return UserModel|Model|bool
     */
    public function findById($id)
    {
        return UserModel::findFirst($id);
    }

    /**
     * @param string $name
     * @return UserModel|Model|bool
     */
    public function findByName($name)
    {
        return UserModel::findFirst([
            'conditions' => 'name = :name:',
            'bind' => ['name' => $name],
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|UserModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return UserModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
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
     * @param int $userId
     * @return ResultsetInterface|Resultset|ImFriendUserModel[]
     */
    public function findImFriendUsers($userId)
    {
        return ImFriendUserModel::query()
            ->where('user_id = :user_id:', ['user_id' => $userId])
            ->execute();
    }

    /**
     * @param int $userId
     * @return ResultsetInterface|Resultset|ImFriendGroupModel[]
     */
    public function findImFriendGroups($userId)
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
    public function findImGroups($userId)
    {
        return $this->modelsManager->createBuilder()
            ->columns('g.*')
            ->addFrom(ImGroupModel::class, 'g')
            ->join(ImGroupUserModel::class, 'g.id = gu.group_id', 'gu')
            ->where('gu.user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('g.deleted = 0')
            ->getQuery()->execute();
    }

    /**
     * @param int $userId
     * @param int $friendId
     * @return ResultsetInterface|Resultset|ImFriendMessageModel[]
     */
    public function findUnreadImFriendMessages($userId, $friendId)
    {
        return ImFriendMessageModel::find([
            'conditions' => 'sender_id = ?1 AND receiver_id = ?2 AND viewed = ?3',
            'bind' => [1 => $userId, 2 => $friendId, 3 => 0],
        ]);
    }

    /**
     * @param int $userId
     * @param int $itemType
     * @return Model|bool|ImSystemMessageModel
     */
    public function findImSystemMessage($userId, $itemType)
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
    public function findUnreadImSystemMessages($userId)
    {
        return ImSystemMessageModel::find([
            'conditions' => 'receiver_id = ?1 AND viewed = ?2',
            'bind' => [1 => $userId, 2 => 0],
        ]);
    }

    public function countUnreadImSystemMessages($userId)
    {
        return ImSystemMessageModel::count([
            'conditions' => 'receiver_id = ?1 AND viewed = ?2',
            'bind' => [1 => $userId, 2 => 0],
        ]);
    }

}
