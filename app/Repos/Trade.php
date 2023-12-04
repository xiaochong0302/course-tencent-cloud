<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Refund as RefundModel;
use App\Models\Trade as TradeModel;
use App\Models\TradeStatus as TradeStatusModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Trade extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(TradeModel::class);

        $builder->where('1 = 1');

        if (!empty($where['id'])) {
            $builder->andWhere('id = :id:', ['id' => $where['id']]);
        }

        if (!empty($where['sn'])) {
            $builder->andWhere('sn = :sn:', ['sn' => $where['sn']]);
        }

        if (!empty($where['owner_id'])) {
            $builder->andWhere('owner_id = :owner_id:', ['owner_id' => $where['owner_id']]);
        }

        if (!empty($where['order_id'])) {
            $builder->andWhere('order_id = :order_id:', ['order_id' => $where['order_id']]);
        }

        if (!empty($where['channel'])) {
            if (is_array($where['channel'])) {
                $builder->inWhere('channel', $where['channel']);
            } else {
                $builder->andWhere('channel = :channel:', ['channel' => $where['channel']]);
            }
        }

        if (!empty($where['status'])) {
            if (is_array($where['status'])) {
                $builder->inWhere('status', $where['status']);
            } else {
                $builder->andWhere('status = :status:', ['status' => $where['status']]);
            }
        }

        if (!empty($where['start_time']) && !empty($where['end_time'])) {
            $startTime = strtotime($where['start_time']);
            $endTime = strtotime($where['end_time']);
            $builder->betweenWhere('create_time', $startTime, $endTime);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
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
     * @return TradeModel|Model|bool
     */
    public function findById($id)
    {
        return TradeModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param string $sn
     * @return TradeModel|Model|bool
     */
    public function findBySn($sn)
    {
        return TradeModel::findFirst([
            'conditions' => 'sn = :sn:',
            'bind' => ['sn' => $sn],
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|TradeModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return TradeModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param int $tradeId
     * @return ResultsetInterface|Resultset|RefundModel[]
     */
    public function findRefunds($tradeId)
    {
        return RefundModel::query()
            ->where('trade_id = :trade_id:', ['trade_id' => $tradeId])
            ->execute();
    }

    /**
     * @param int $tradeId
     * @return ResultsetInterface|Resultset|TradeStatusModel[]
     */
    public function findStatusHistory($tradeId)
    {
        return TradeStatusModel::query()
            ->where('trade_id = :trade_id:', ['trade_id' => $tradeId])
            ->execute();
    }

    /**
     * @param int $tradeId
     * @return RefundModel|Model|bool
     */
    public function findLastRefund($tradeId)
    {
        return RefundModel::findFirst([
            'conditions' => 'trade_id = :trade_id:',
            'bind' => ['trade_id' => $tradeId],
            'order' => 'id DESC',
        ]);
    }

}
