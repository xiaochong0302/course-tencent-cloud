<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\PointRedeem as PointRedeemModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class PointRedeem extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(PointRedeemModel::class);

        $builder->where('1 = 1');

        if (!empty($where['user_id'])) {
            $builder->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
        }

        if (!empty($where['gift_id'])) {
            $builder->andWhere('gift_id = :gift_id:', ['gift_id' => $where['gift_id']]);
        }

        if (!empty($where['gift_type'])) {
            $builder->andWhere('gift_type = :gift_type:', ['gift_type' => $where['gift_type']]);
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
     * @return PointRedeemModel|Model|bool
     */
    public function findById($id)
    {
        return PointRedeemModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param array $ids
     * @param string|array $columns
     * @return ResultsetInterface|Resultset|PointRedeemModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return PointRedeemModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

}
