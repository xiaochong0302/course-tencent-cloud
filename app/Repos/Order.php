<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Order as OrderModel;
use App\Models\OrderStatus as OrderStatusModel;
use App\Models\Refund as RefundModel;
use App\Models\Trade as TradeModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\Model\Row;
use Phalcon\Paginator\RepositoryInterface as PagerRepoInterface;

class Order extends Repository
{

    /**
     * @param array $where
     * @param string $sort
     * @param int $page
     * @param int $limit
     * @return PagerRepoInterface
     */
    public function paginate(array $where = [], string $sort = 'latest', int $page = 1, int $limit = 15): PagerRepoInterface
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

        if (!empty($where['promotion_id'])) {
            $builder->andWhere('promotion_id = :promotion_id:', ['promotion_id' => $where['promotion_id']]);
        }

        if (!empty($where['promotion_type'])) {
            if (is_array($where['promotion_type'])) {
                $builder->inWhere('promotion_type', $where['promotion_type']);
            } else {
                $builder->andWhere('promotion_type = :promotion_type:', ['promotion_type' => $where['promotion_type']]);
            }
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

        if (!empty($where['create_time'][0]) && !empty($where['create_time'][1])) {
            $startTime = strtotime($where['create_time'][0]);
            $endTime = strtotime($where['create_time'][1]);
            $builder->betweenWhere('create_time', $startTime, $endTime);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        $orderBy = match ($sort) {
            'oldest' => 'id ASC',
            default => 'id DESC',
        };

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
     * @return OrderModel|Row|null
     */
    public function findById(int $id)
    {
        return OrderModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param string $sn
     * @return OrderModel|Row|null
     */
    public function findBySn(string $sn)
    {
        return OrderModel::findFirst([
            'conditions' => 'sn = :sn:',
            'bind' => ['sn' => $sn],
        ]);
    }

    /**
     * @param int $userId
     * @param int $itemId
     * @param int $itemType
     * @return OrderModel|Row|null
     */
    public function findUserLastPendingOrder(int $userId, int $itemId, int $itemType)
    {
        $status = OrderModel::STATUS_PENDING;

        return $this->findUserLastStatusOrder($userId, $itemId, $itemType, $status);
    }

    /**
     * @param int $userId
     * @param int $itemId
     * @param int $itemType
     * @return OrderModel|Row|null
     */
    public function findUserLastDeliveringOrder(int $userId, int $itemId, int $itemType)
    {
        $status = OrderModel::STATUS_DELIVERING;

        return $this->findUserLastStatusOrder($userId, $itemId, $itemType, $status);
    }

    /**
     * @param int $userId
     * @param int $itemId
     * @param int $itemType
     * @return OrderModel|Row|null
     */
    public function findUserLastFinishedOrder(int $userId, int $itemId, int $itemType)
    {
        $status = OrderModel::STATUS_FINISHED;

        return $this->findUserLastStatusOrder($userId, $itemId, $itemType, $status);
    }

    /**
     * @param int $userId
     * @param int $itemId
     * @param int $itemType
     * @param int $status
     * @return OrderModel|Row|null
     */
    public function findUserLastStatusOrder(int $userId, int $itemId, int $itemType, int $status)
    {
        return OrderModel::findFirst([
            'conditions' => 'owner_id = ?1 AND item_id = ?2 AND item_type = ?3 AND status = ?4',
            'bind' => [1 => $userId, 2 => $itemId, 3 => $itemType, 4 => $status],
            'order' => 'id DESC',
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|OrderModel[]
     */
    public function findByIds(array $ids, array|string $columns = '*')
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
    public function findTrades(int $orderId)
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
    public function findRefunds(int $orderId)
    {
        return RefundModel::query()
            ->where('order_id = :order_id:', ['order_id' => $orderId])
            ->andWhere('deleted = 0')
            ->execute();
    }

    /**
     * @param int $orderId
     * @return ResultsetInterface|Resultset|OrderStatusModel[]
     */
    public function findStatusHistory(int $orderId)
    {
        return OrderStatusModel::query()
            ->where('order_id = :order_id:', ['order_id' => $orderId])
            ->execute();
    }

    /**
     * @param int $orderId
     * @return TradeModel|Row|null
     */
    public function findLastTrade(int $orderId)
    {
        return TradeModel::findFirst([
            'conditions' => 'order_id = :order_id:',
            'bind' => ['order_id' => $orderId],
            'order' => 'id DESC',
        ]);
    }

    /**
     * @param int $orderId
     * @return TradeModel|Row|null
     */
    public function findFinishedTrade(int $orderId)
    {
        $status = TradeModel::STATUS_FINISHED;

        return TradeModel::findFirst([
            'conditions' => 'order_id = :order_id: AND status = :status:',
            'bind' => ['order_id' => $orderId, 'status' => $status],
            'order' => 'id DESC',
        ]);
    }

    /**
     * @param int $orderId
     * @return RefundModel|Row|null
     */
    public function findLastRefund(int $orderId)
    {
        return RefundModel::findFirst([
            'conditions' => 'order_id = :order_id:',
            'bind' => ['order_id' => $orderId],
            'order' => 'id DESC',
        ]);
    }

}
