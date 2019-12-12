<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Learning as LearningModel;

class Learning extends Repository
{

    /**
     * @param string $requestId
     * @return LearningModel $result
     */
    public function findByRequestId($requestId)
    {
        $result = LearningModel::findFirstByRequestId($requestId);

        return $result;
    }

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(LearningModel::class);

        $builder->where('1 = 1');

        if (!empty($where['user_id'])) {
            $builder->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
        }

        if (!empty($where['course_id'])) {
            $builder->andWhere('course_id = :course_id:', ['course_id' => $where['course_id']]);
        }

        if (!empty($where['chapter_id'])) {
            $builder->andWhere('chapter_id = :chapter_id:', ['chapter_id' => $where['chapter_id']]);
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
