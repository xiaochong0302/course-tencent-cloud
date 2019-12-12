<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Audit as AuditModel;

class Audit extends Repository
{

    /**
     * @param integer $id
     * @return AuditModel
     */
    public function findById($id)
    {
        $result = AuditModel::findFirstById($id);

        return $result;
    }

    public function findByIds($ids, $columns = '*')
    {
        $result = AuditModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

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

        return $pager->getPaginate();
    }

}
