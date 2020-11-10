<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\App as AppModel;
use Phalcon\Mvc\Model;

class App extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(AppModel::class);

        $builder->where('1 = 1');

        if (!empty($where['id'])) {
            $builder->andWhere('id = :id:', ['id' => $where['id']]);
        }

        if (!empty($where['key'])) {
            $builder->andWhere('key = :key:', ['key' => $where['key']]);
        }

        if (!empty($where['type'])) {
            $builder->andWhere('type = :type:', ['type' => $where['type']]);
        }

        if (!empty($where['published'])) {
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
     * @return AppModel|Model|bool
     */
    public function findById($id)
    {
        return AppModel::findFirst($id);
    }

    /**
     * @param string $appKey
     * @return AppModel|Model|bool
     */
    public function findByAppKey($appKey)
    {
        return AppModel::findFirst([
            'conditions' => 'key = :key:',
            'bind' => ['key' => $appKey],
        ]);
    }

}
