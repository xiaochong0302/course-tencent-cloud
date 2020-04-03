<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Refund as RefundModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Refund extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(RefundModel::class);

        $builder->where('1 = 1');

        if (!empty($where['user_id'])) {
            $builder->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
        }

        if (!empty($where['order_id'])) {
            $builder->andWhere('order_id = :order_id:', ['order_id' => $where['order_id']]);
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
     * @return RefundModel|Model|bool
     */
    public function findById($id)
    {
        return RefundModel::findFirst($id);
    }

    /**
     * @param string $sn
     * @return RefundModel|Model|bool
     */
    public function findBySn($sn)
    {
        return RefundModel::findFirst([
            'conditions' => 'sn = :sn:',
            'bind' => ['sn' => $sn],
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|RefundModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return RefundModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

}
