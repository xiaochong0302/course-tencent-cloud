<?php

namespace App\Repos;

use App\Models\CourseFavorite as CourseFavoriteModel;

class CourseFavorite extends Repository
{

    public function findCourseFavorite($courseId, $userId)
    {
        $result = CourseFavoriteModel::query()
            ->where('user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('course_id = :course_id:', ['course_id' => $courseId])
            ->execute()->getFirst();

        return $result;
    }

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(CourseFavoriteModel::class);

        $builder->where('1 = 1');

        if (!empty($where['course_id'])) {
            $builder->andWhere('course_id = :course_id:', ['course_id' => $where['course_id']]);
        }

        if (!empty($where['user_id'])) {
            $builder->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
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

        return $pager;
    }

}
