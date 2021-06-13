<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\ImGroupUser as ImGroupUserModel;
use Phalcon\Mvc\Model;

class ImGroupUser extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(ImGroupUserModel::class);

        $builder->where('1 = 1');

        if (!empty($where['group_id'])) {
            $builder->andWhere('group_id = :group_id:', ['group_id' => $where['group_id']]);
        }

        if (!empty($where['user_id'])) {
            $builder->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
        }

        if (isset($where['blocked'])) {
            $builder->andWhere('blocked = :blocked:', ['blocked' => $where['blocked']]);
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
     * @param int $groupId
     * @param int $userId
     * @return ImGroupUserModel|Model|bool
     */
    public function findGroupUser($groupId, $userId)
    {
        return ImGroupUserModel::findFirst([
            'conditions' => 'group_id = ?1 AND user_id = ?2',
            'bind' => [1 => $groupId, 2 => $userId],
            'order' => 'id DESC',
        ]);
    }

}
