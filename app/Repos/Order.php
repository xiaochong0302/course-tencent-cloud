<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Order as OrderModel;
use App\Models\Refund as RefundModel;
use App\Models\Trade as TradeModel;

class Order extends Repository
{

    /**
     * @param int $id
     * @return OrderModel
     */
    public function findById($id)
    {
        $result = OrderModel::findFirst($id);

        return $result;
    }

    /**
     * @param string $sn
     * @return OrderModel
     */
    public function findBySn($sn)
    {
        $result = OrderModel::findFirst([
            'conditions' => 'sn = :sn:',
            'bind' => ['sn' => $sn],
        ]);

        return $result;
    }

    public function findByIds($ids, $columns = '*')
    {
        $result = OrderModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

    public function findTrades($orderSn)
    {
        $result = TradeModel::query()
            ->where('order_sn = :order_sn:', ['order_sn' => $orderSn])
            ->andWhere('deleted = 0')
            ->execute();

        return $result;
    }

    public function findRefunds($orderSn)
    {
        $result = RefundModel::query()
            ->where('order_sn = :order_sn:', ['order_sn' => $orderSn])
            ->andWhere('deleted = 0')
            ->execute();

        return $result;
    }

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(OrderModel::class);

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

        if (!empty($where['item_id'])) {
            $builder->andWhere('item_id = :item_id:', ['item_id' => $where['item_id']]);
        }

        if (!empty($where['item_type'])) {
            $builder->andWhere('item_type = :item_type:', ['item_type' => $where['item_type']]);
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
