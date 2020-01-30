<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Refund as RefundModel;
use App\Models\Trade as TradeModel;

class Trade extends Repository
{

    /**
     * @param int $id
     * @return TradeModel
     */
    public function findById($id)
    {
        $result = TradeModel::findFirst($id);

        return $result;
    }

    /**
     * @param string $sn
     * @return TradeModel
     */
    public function findBySn($sn)
    {
        $result = TradeModel::findFirst([
            'conditions' => 'sn = :sn:',
            'bind' => ['sn' => $sn],
        ]);

        return $result;
    }

    public function findByIds($ids, $columns = '*')
    {
        $result = TradeModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

    public function findRefunds($tradeSn)
    {
        $result = RefundModel::query()
            ->where('trade_sn = :trade_sn:', ['trade_sn' => $tradeSn])
            ->execute();

        return $result;
    }

    public function findLatestRefund($tradeSn)
    {
        $result = RefundModel::query()
            ->where('trade_sn = :trade_sn:', ['trade_sn' => $tradeSn])
            ->orderBy('id DESC')
            ->execute()
            ->getFirst();

        return $result;
    }

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

        if (!empty($where['order_sn'])) {
            $builder->andWhere('order_sn = :order_sn:', ['order_sn' => $where['order_sn']]);
        }

        if (!empty($where['channel'])) {
            $builder->andWhere('channel = :channel:', ['channel' => $where['channel']]);
        }

        if (!empty($where['status'])) {
            $builder->andWhere('status = :status:', ['status' => $where['status']]);
        }

        if (!empty($where['start_time']) && !empty($where['end_time'])) {
            $startTime = strtotime($where['start_time']);
            $endTime = strtotime($where['end_time']);
            $builder->betweenWhere('created_at', $startTime, $endTime);
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

}
