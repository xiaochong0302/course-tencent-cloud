<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\FlashSale as FlashSaleModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class FlashSale extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(FlashSaleModel::class);

        $builder->where('1 = 1');

        if (!empty($where['id'])) {
            $builder->andWhere('id = :id:', ['id' => $where['id']]);
        }

        if (!empty($where['item_id'])) {
            $builder->andWhere('item_id = :item_id:', ['item_id' => $where['item_id']]);
        }

        if (!empty($where['item_type'])) {
            $builder->andWhere('item_type = :item_type:', ['item_type' => $where['item_type']]);
        }

        if (isset($where['published'])) {
            $builder->andWhere('published = :published:', ['published' => $where['published']]);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        $now = time();

        if (!empty($where['status'])) {
            switch ($where['status']) {
                case 'pending':
                    $builder->andWhere('start_time > :start_time:', ['start_time' => $now]);
                    break;
                case 'finished':
                    $builder->andWhere('end_time < :end_time:', ['end_time' => $now]);
                    break;
                case 'active':
                    $builder->andWhere('start_time < :start_time:', ['start_time' => $now]);
                    $builder->andWhere('end_time > :end_time:', ['end_time' => $now]);
                    break;
            }
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
     * @param string $date
     * @return ResultsetInterface|Resultset|FlashSaleModel[]
     */
    public function findFutureSales($date)
    {
        $time = strtotime($date);

        return FlashSaleModel::query()
            ->where('published = 1')
            ->andWhere('end_time > :time:', ['time' => $time])
            ->execute();
    }

    /**
     * @param int $id
     * @return FlashSaleModel|Model|bool
     */
    public function findById($id)
    {
        return FlashSaleModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param array $ids
     * @param string|array $columns
     * @return ResultsetInterface|Resultset|FlashSaleModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return FlashSaleModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

}
