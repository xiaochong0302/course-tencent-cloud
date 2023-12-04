<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Report as ReportModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Report extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(ReportModel::class);

        $builder->where('1 = 1');

        if (!empty($where['owner_id'])) {
            $builder->andWhere('owner_id = :owner_id:', ['owner_id' => $where['owner_id']]);
        }

        if (!empty($where['item_id'])) {
            $builder->andWhere('item_id = :item_id:', ['item_id' => $where['item_id']]);
        }

        if (!empty($where['item_type'])) {
            if (is_array($where['item_type'])) {
                $builder->inWhere('item_type', $where['item_type']);
            } else {
                $builder->andWhere('item_type = :item_type:', ['item_type' => $where['item_type']]);
            }
        }

        if (!empty($where['create_time'][0]) && !empty($where['create_time'][1])) {
            $startTime = strtotime($where['create_time'][0]);
            $endTime = strtotime($where['create_time'][1]);
            $builder->betweenWhere('create_time', $startTime, $endTime);
        }

        if (isset($where['reviewed'])) {
            $builder->andWhere('reviewed = :reviewed:', ['reviewed' => $where['reviewed']]);
        }

        if (isset($where['accepted'])) {
            $builder->andWhere('accepted = :accepted:', ['accepted' => $where['accepted']]);
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
     * @param int $id
     * @return ReportModel|Model|bool
     */
    public function findById($id)
    {
        return ReportModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|ReportModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return ReportModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    public function findUserReport($userId, $itemId, $itemType)
    {
        return ReportModel::findFirst([
            'conditions' => 'owner_id = ?1 AND item_id = ?2 AND item_type = ?3',
            'bind' => [1 => $userId, 2 => $itemId, 3 => $itemType],
        ]);
    }

    /**
     * @param int $itemId
     * @param int $itemType
     * @return ResultsetInterface|Resultset|ReportModel[]
     */
    public function findItemPendingReports($itemId, $itemType)
    {
        return ReportModel::query()
            ->where('item_id = :item_id:', ['item_id' => $itemId])
            ->andWhere('item_type = :item_type:', ['item_type' => $itemType])
            ->andWhere('reviewed = 0')
            ->execute();
    }

}
