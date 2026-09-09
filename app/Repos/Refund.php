<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Refund as RefundModel;
use App\Models\RefundStatus as RefundStatusModel;
use App\Models\Task as TaskModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\Model\Row;
use Phalcon\Paginator\RepositoryInterface as PagerRepoInterface;

class Refund extends Repository
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

        $builder->from(RefundModel::class);

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

        if (!empty($where['trade_id'])) {
            $builder->andWhere('trade_id = :trade_id:', ['trade_id' => $where['trade_id']]);
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
     * @return RefundModel|Row|null
     */
    public function findById(int $id)
    {
        return RefundModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param string $sn
     * @return RefundModel|Row|null
     */
    public function findBySn(string $sn)
    {
        return RefundModel::findFirst([
            'conditions' => 'sn = :sn:',
            'bind' => ['sn' => $sn],
        ]);
    }

    /**
     * @param int $refundId
     * @return ResultsetInterface|Resultset|RefundStatusModel[]
     */
    public function findStatusHistory(int $refundId)
    {
        return RefundStatusModel::query()
            ->where('refund_id = :refund_id:', ['refund_id' => $refundId])
            ->execute();
    }

    /**
     * @param int $refundId
     * @return TaskModel|Row|null
     */
    public function findLastRefundTask(int $refundId)
    {
        return TaskModel::findFirst([
            'conditions' => 'item_id = ?1 AND item_type = ?2',
            'bind' => [1 => $refundId, 2 => TaskModel::TYPE_REFUND_APPLY],
            'order' => 'id DESC',
        ]);
    }

}
