<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Slide as SlideModel;

class Slide extends Repository
{

    /**
     * @param integer $id
     * @return SlideModel
     */
    public function findById($id)
    {
        $result = SlideModel::findFirstById($id);

        return $result;
    }

    public function findByIds($ids, $columns = '*')
    {
        $result = SlideModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

    public function findTopSlides($limit = 5)
    {
        $result = SlideModel::query()
            ->andWhere('published = :published:', ['published' => 1])
            ->andWhere('deleted = :deleted:', ['deleted' => 0])
            ->orderBy('priority ASC')
            ->limit($limit)
            ->execute();

        return $result;
    }

    public function paginate($where = [], $sort = 'priority', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(SlideModel::class);

        $builder->where('1 = 1');

        if (isset($where['item_type'])) {
            $builder->andWhere('item_type = :item_type:', ['item_type' => $where['item_type']]);
        }

        if (isset($where['published'])) {
            $builder->andWhere('published = :published:', ['published' => $where['published']]);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        switch ($sort) {
            default:
                $orderBy = 'priority ASC';
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
