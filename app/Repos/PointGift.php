<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\PointGift as PointGiftModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class PointGift extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(PointGiftModel::class);

        $builder->where('1 = 1');

        if (!empty($where['id'])) {
            $builder->andWhere('id = :id:', ['id' => $where['id']]);
        }

        if (!empty($where['name'])) {
            $builder->andWhere('name LIKE :name:', ['name' => "%{$where['name']}%"]);
        }

        if (!empty($where['type'])) {
            if (is_array($where['type'])) {
                $builder->inWhere('type', $where['type']);
            } else {
                $builder->andWhere('type = :type:', ['type' => $where['type']]);
            }
        }

        if (!empty($where['create_time'][0]) && !empty($where['create_time'][1])) {
            $startTime = strtotime($where['create_time'][0]);
            $endTime = strtotime($where['create_time'][1]);
            $builder->betweenWhere('create_time', $startTime, $endTime);
        }

        if (isset($where['published'])) {
            $builder->andWhere('published = :published:', ['published' => $where['published']]);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        switch ($sort) {
            case 'popular':
                $orderBy = 'redeem_count DESC, id DESC';
                break;
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
     * @param int $id
     * @return PointGiftModel|Model|bool
     */
    public function findById($id)
    {
        return PointGiftModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param int $itemId
     * @param int $itemType
     * @return PointGiftModel|Model|bool
     */
    public function findItemGift($itemId, $itemType)
    {
        /**
         * @todo 重新设计表结构
         *
         * 没有预留独立的条目编号，先这么将就实现吧
         */
        $records = PointGiftModel::query()
            ->where('type = :type:', ['type' => $itemType])
            ->execute();

        if ($records->count() == 0) return false;

        foreach ($records as $record) {
            if ($record->attrs['id'] == $itemId) {
                return $record;
            }
        }

        return false;
    }

    /**
     * @param array $ids
     * @param string|array $columns
     * @return ResultsetInterface|Resultset|PointGiftModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return PointGiftModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

}
