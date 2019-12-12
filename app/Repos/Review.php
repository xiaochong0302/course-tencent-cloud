<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Review as ReviewModel;

class Review extends Repository
{

    /**
     * @param integer $id
     * @return ReviewModel
     */
    public function findById($id)
    {
        $result = ReviewModel::findFirstById($id);

        return $result;
    }

    public function findByIds($ids, $columns = '*')
    {
        $result = ReviewModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

    public function findByUserCourseId($userId, $courseId)
    {
        $result = ReviewModel::query()
            ->where('user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('course_id = :course_id:', ['course_id' => $courseId])
            ->execute()
            ->getFirst();

        return $result;
    }

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(ReviewModel::class);

        $builder->where('1 = 1');

        if (isset($where['id'])) {
            $builder->andWhere('id = :id:', ['id' => $where['id']]);
        }

        if (isset($where['user_id'])) {
            $builder->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
        }

        if (isset($where['course_id'])) {
            $builder->andWhere('course_id = :course_id:', ['course_id' => $where['course_id']]);
        }

        if (isset($where['published'])) {
            $builder->andWhere('published = :published:', ['published' => $where['published']]);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        if (isset($where['rating'])) {
            switch ($where['rating']) {
                case 'good':
                    $builder->andWhere('rating = 5');
                    break;
                case 'normal':
                    $builder->betweenWhere('rating', 3, 4);
                    break;
                case 'bad':
                    $builder->andWhere('rating < 3');
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

        return $pager->getPaginate();
    }

}
