<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\ImFriendUser as ImFriendUserModel;
use Phalcon\Mvc\Model;

class ImFriendUser extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(ImFriendUserModel::class);

        $builder->where('1 = 1');

        if (!empty($where['user_id'])) {
            $builder->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
        }

        if (!empty($where['friend_id'])) {
            $builder->andWhere('friend_id = :friend_id:', ['friend_id' => $where['friend_id']]);
        }

        switch ($sort) {
            case 'oldest':
                $orderBy = 'id ASC';
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
     * @param int $userId
     * @param int $friendId
     * @return ImFriendUserModel|Model|bool
     */
    public function findFriendUser($userId, $friendId)
    {
        return ImFriendUserModel::findFirst([
            'conditions' => 'user_id = ?1 AND friend_id = ?2',
            'bind' => [1 => $userId, 2 => $friendId],
            'order' => 'id DESC',
        ]);
    }

}
