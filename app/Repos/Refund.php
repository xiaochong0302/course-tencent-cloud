<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Refund as RefundModel;

class Refund extends Repository
{

    /**
     * @param integer $id
     * @return RefundModel
     */
    public function findById($id)
    {
        $result = RefundModel::findFirstById($id);

        return $result;
    }

    /**
     * @param string $sn
     * @return RefundModel
     */
    public function findBySn($sn)
    {
        $result = RefundModel::findFirstBySn($sn);

        return $result;
    }

    public function findByIds($ids, $columns = '*')
    {
        $result = RefundModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(RefundModel::class);

        $builder->where('1 = 1');

        if (!empty($where['user_id'])) {
            $builder->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
        }

        if (!empty($where['order_sn'])) {
            $builder->andWhere('order_sn = :order_sn:', ['order_sn' => $where['order_sn']]);
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

        return $pager->getPaginate();
    }

}
