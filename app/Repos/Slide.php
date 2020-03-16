<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Slide as SlideModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Slide extends Repository
{

    /**
     * @param array $where
     * @param string $sort
     * @param int $page
     * @param int $limit
     * @return \stdClass
     */
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

        return $pager->paginate();
    }

    /**
     * @param int $id
     * @return SlideModel|Model|bool
     */
    public function findById($id)
    {
        $result = SlideModel::findFirst($id);

        return $result;
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|SlideModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        $result = SlideModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|SlideModel[]
     */
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

}
