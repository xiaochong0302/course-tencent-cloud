<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Order as OrderModel;
use App\Models\OrderStatus as OrderStatusModel;
use App\Models\Refund as RefundModel;
use App\Models\Trade as TradeModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Order extends Repository
{

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

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        if (!empty($where['start_time']) && !empty($where['end_time'])) {
            $startTime = strtotime($where['start_time']);
            $endTime = strtotime($where['end_time']);
            $builder->betweenWhere('create_time', $startTime, $endTime);
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
     * @return OrderModel|Model|bool
     */
    public function findById($id)
    {
        return OrderModel::findFirst($id);
    }

    /**
     * @param string $sn
     * @return OrderModel|Model|bool
     */
    public function findBySn($sn)
    {
        return OrderModel::findFirst([
            'conditions' => 'sn = :sn:',
            'bind' => ['sn' => $sn],
        ]);
    }

    /**
     * @param int $userId
     * @param string $itemId
     * @param string $itemType
     * @return OrderModel|Model|bool
     */
    public function findUserLastFinishedOrder($userId, $itemId, $itemType)
    {
        $status = OrderModel::STATUS_FINISHED;

        return OrderModel::findFirst([
            'conditions' => 'user_id = ?1 AND item_id = ?2 AND item_type = ?3 AND status = ?4',
            'bind' => [1 => $userId, 2 => $itemId, 3 => $itemType, 4 => $status],
            'order' => 'id DESC',
        ]);
    }

    /**
     * @param int $userId
     * @param string $itemId
     * @param string $itemType
     * @return OrderModel|Model|bool
     */
    public function findUserLastPendingOrder($userId, $itemId, $itemType)
    {
        $status = OrderModel::STATUS_PENDING;

        return OrderModel::findFirst([
            'conditions' => 'user_id = ?1 AND item_id = ?2 AND item_type = ?3 AND status= ?4',
            'bind' => [1 => $userId, 2 => $itemId, 3 => $itemType, 4 => $status],
            'order' => 'id DESC',
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|OrderModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return OrderModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param int $orderId
     * @return ResultsetInterface|Resultset|TradeModel[]
     */
    public function findTrades($orderId)
    {
        return TradeModel::query()
            ->where('order_id = :order_id:', ['order_id' => $orderId])
            ->andWhere('deleted = 0')
            ->execute();
    }

    /**
     * @param int $orderId
     * @return ResultsetInterface|Resultset|RefundModel[]
     */
    public function findRefunds($orderId)
    {
        return RefundModel::query()
            ->where('order_id = :order_id:', ['order_id' => $orderId])
            ->andWhere('deleted = 0')
            ->execute();
    }

    /**
     * @param $orderId
     * @return ResultsetInterface|Resultset|OrderStatusModel[]
     */
    public function findStatusHistory($orderId)
    {
        return OrderStatusModel::query()
            ->where('order_id = :order_id:', ['order_id' => $orderId])
            ->execute();
    }

    /**
     * @param int $orderId
     * @return TradeModel|Model|bool
     */
    public function findLastTrade($orderId)
    {
        return TradeModel::findFirst([
            'conditions' => 'order_id = :order_id:',
            'bind' => ['order_id' => $orderId],
            'order' => 'id DESC',
        ]);
    }

    /**
     * @param int $orderId
     * @return RefundModel|Model|bool
     */
    public function findLastRefund($orderId)
    {
        return RefundModel::findFirst([
            'conditions' => 'order_id = :order_id:',
            'bind' => ['order_id' => $orderId],
            'order' => 'id DESC',
        ]);
    }

}
