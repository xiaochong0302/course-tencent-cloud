<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\TagFollow as TagFollowModel;
use Phalcon\Mvc\Model;

class TagFollow extends Repository
{


    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(TagFollowModel::class);

        $builder->where('1 = 1');

        if (!empty($where['tag_id'])) {
            $builder->andWhere('tag_id = :tag_id:', ['tag_id' => $where['tag_id']]);
        }

        if (!empty($where['user_id'])) {
            $builder->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
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
     * @param int $tagId
     * @param int $userId
     * @return TagFollowModel|Model|bool
     */
    public function findTagFollow($tagId, $userId)
    {
        return TagFollowModel::findFirst([
            'conditions' => 'tag_id = :tag_id: AND user_id = :user_id:',
            'bind' => ['tag_id' => $tagId, 'user_id' => $userId],
        ]);
    }

}
