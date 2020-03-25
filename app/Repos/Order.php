<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Order as OrderModel;
use App\Models\Refund as RefundModel;
use App\Models\Trade as TradeModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Order extends Repository
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

    /**
     * @param int $id
     * @return OrderModel|Model|bool
     */
    public function findById($id)
    {
        $result = OrderModel::findFirst($id);

        return $result;
    }

    /**
     * @param string $sn
     * @return OrderModel|Model|bool
     */
    public function findBySn($sn)
    {
        $result = OrderModel::findFirst([
            'conditions' => 'sn = :sn:',
            'bind' => ['sn' => $sn],
        ]);

        return $result;
    }

    /**
     * @param int $userId
     * @param string $itemId
     * @param string $itemType
     * @return OrderModel|Model|bool
     */
    public function findFinishedUserOrder($userId, $itemId, $itemType)
    {
        $status = OrderModel::STATUS_FINISHED;

        $result = OrderModel::findFirst([
            'conditions' => 'user_id = ?1 AND item_id = ?2 AND item_type = ?3 AND status = ?4',
            'bind' => [1 => $userId, 2 => $itemId, 3 => $itemType, 4 => $status],
            'order' => 'id DESC',
        ]);

        return $result;
    }

    /**
     * @param int $userId
     * @param string $itemId
     * @param string $itemType
     * @return OrderModel|Model|bool
     */
    public function findLastUserItem($userId, $itemId, $itemType)
    {
        $result = OrderModel::findFirst([
            'conditions' => 'user_id = ?1 AND item_id = ?2 AND item_type = ?3',
            'bind' => [1 => $userId, 2 => $itemId, 3 => $itemType],
            'order' => 'id DESC',
        ]);

        return $result;
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|OrderModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        $result = OrderModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

    /**
     * @param int $orderId
     * @return ResultsetInterface|Resultset|TradeModel[]
     */
    public function findTrades($orderId)
    {
        $result = TradeModel::query()
            ->where('order_id = :order_id:', ['order_id' => $orderId])
            ->andWhere('deleted = 0')
            ->execute();

        return $result;
    }

    /**
     * @param int $orderId
     * @return ResultsetInterface|Resultset|RefundModel[]
     */
    public function findRefunds($orderId)
    {
        $result = RefundModel::query()
            ->where('order_id = :order_id:', ['order_id' => $orderId])
            ->andWhere('deleted = 0')
            ->execute();

        return $result;
    }

    public function countUserDailyOrders($userId)
    {
        $createdAt = strtotime(date('Y-m-d'));

        $count = OrderModel::count([
            'conditions' => 'user_id = :user_id: AND created_at > :created_at:',
            'bind' => ['user_id' => $userId, 'created_at' => $createdAt],
        ]);

        return $count;
    }

}
