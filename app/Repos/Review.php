<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Review as ReviewModel;
use App\Models\ReviewVote as ReviewVoteModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Review extends Repository
{

    /**
     * @param array $where
     * @param string $sort
     * @param int $page
     * @param int $limit
     * @return \stdClass
     */
    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(ReviewModel::class);

        $builder->where('1 = 1');

        if (!empty($where['id'])) {
            $builder->andWhere('id = :id:', ['id' => $where['id']]);
        }

        if (!empty($where['course_id'])) {
            $builder->andWhere('course_id = :course_id:', ['course_id' => $where['course_id']]);
        }

        if (!empty($where['user_id'])) {
            $builder->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
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

        return $pager->paginate();
    }

    /**
     * @param int $id
     * @return ReviewModel|Model|bool
     */
    public function findById($id)
    {
        $result = ReviewModel::findFirst($id);

        return $result;
    }

    /**
     * @param int $courseId
     * @param int $userId
     * @return ReviewModel|Model|bool
     */
    public function findReview($courseId, $userId)
    {
        $result = ReviewModel::findFirst([
            'conditions' => 'course_id = :course_id: AND user_id = :user_id:',
            'bind' => ['course_id' => $courseId, 'user_id' => $userId],
        ]);

        return $result;
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|ReviewModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        $result = ReviewModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

    public function countAgrees($reviewId)
    {
        $type = ReviewVoteModel::TYPE_AGREE;

        $count = ReviewVoteModel::count([
            'conditions' => 'review_id = :review_id: AND type = :type: AND deleted = 0',
            'bind' => ['review_id' => $reviewId, 'type' => $type],
        ]);

        return $count;
    }

    public function countOpposes($reviewId)
    {
        $type = ReviewVoteModel::TYPE_OPPOSE;

        $count = ReviewVoteModel::count([
            'conditions' => 'review_id = :review_id: AND type = :type: AND deleted = 0',
            'bind' => ['review_id' => $reviewId, 'type' => $type],
        ]);

        return $count;
    }

}
