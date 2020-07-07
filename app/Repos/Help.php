<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Help as HelpModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Help extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(HelpModel::class);

        $builder->where('1 = 1');

        if (!empty($where['title'])) {
            $builder->andWhere('title LIKE :title:', ['title' => "%{$where['title']}%"]);
        }

        if (isset($where['published'])) {
            $builder->andWhere('published = :published:', ['published' => $where['published']]);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
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
     * @return HelpModel|Model|bool
     */
    public function findById($id)
    {
        return HelpModel::findFirst($id);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|HelpModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return HelpModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param array $where
     * @return ResultsetInterface|Resultset|HelpModel[]
     */
    public function findAll($where = [])
    {
        $query = HelpModel::query();

        $query->where('1 = 1');

        if (isset($where['published'])) {
            $query->andWhere('published = :published:', ['published' => $where['published']]);
        }

        if (isset($where['deleted'])) {
            $query->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        $query->orderBy('priority ASC');

        return $query->execute();
    }

}
