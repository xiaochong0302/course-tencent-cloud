<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Refund as RefundModel;
use App\Models\Trade as TradeModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Trade extends Repository
{

    /**
     * @param array $where
     * @param string $sort
     * @param int $page
     * @param int $limit
     * @return \stdClass
     */
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

        if (!empty($where['user_id'])) {
            $builder->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
        }

        if (!empty($where['order_id'])) {
            $builder->andWhere('order_id = :order_id:', ['order_id' => $where['order_id']]);
        }

        if (!empty($where['channel'])) {
            $builder->andWhere('channel = :channel:', ['channel' => $where['channel']]);
        }

        if (!empty($where['status'])) {
            $builder->andWhere('status = :status:', ['status' => $where['status']]);
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
     * @return TradeModel|Model|bool
     */
    public function findById($id)
    {
        $result = TradeModel::findFirst($id);

        return $result;
    }

    /**
     * @param string $sn
     * @return TradeModel|Model|bool
     */
    public function findBySn($sn)
    {
        $result = TradeModel::findFirst([
            'conditions' => 'sn = :sn:',
            'bind' => ['sn' => $sn],
        ]);

        return $result;
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|TradeModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        $result = TradeModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

    /**
     * @param int $tradeId
     * @return ResultsetInterface|Resultset|RefundModel[]
     */
    public function findRefunds($tradeId)
    {
        $result = RefundModel::query()
            ->where('trade_id = :trade_id:', ['trade_id' => $tradeId])
            ->execute();

        return $result;
    }

    /**
     * @param int $tradeId
     * @return RefundModel|Model|bool
     */
    public function findLastRefund($tradeId)
    {
        $result = RefundModel::findFirst([
            'conditions' => 'trade_id = :trade_id:',
            'bind' => ['trade_id' => $tradeId],
            'order' => 'id DESC',
        ]);

        return $result;
    }

}
