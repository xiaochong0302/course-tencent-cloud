<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Audit as AuditModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Audit extends Repository
{

    /**
     * @param array $where
     * @param string $sort
     * @param int $page
     * @param int $limit
     * @return object
     */
    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(AuditModel::class);

        $builder->where('1 = 1');

        if (!empty($where['user_id'])) {
            $builder->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
        }

        if (!empty($where['user_name'])) {
            $builder->andWhere('user_name = :user_name:', ['user_name' => $where['user_name']]);
        }

        if (!empty($where['req_route'])) {
            $builder->andWhere('req_route = :req_route:', ['req_route' => $where['req_route']]);
        }

        if (!empty($where['req_path'])) {
            $builder->andWhere('req_path = :req_path:', ['req_path' => $where['req_path']]);
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
     * @param string $id
     * @return AuditModel|Model|bool
     */
    public function findById($id)
    {
        $result = AuditModel::findFirst($id);

        return $result;
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|AuditModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        $result = AuditModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }


}
