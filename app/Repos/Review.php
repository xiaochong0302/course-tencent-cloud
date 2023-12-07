<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Review as ReviewModel;
use App\Models\ReviewLike as ReviewLikeModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Review extends Repository
{

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

        if (!empty($where['owner_id'])) {
            $builder->andWhere('owner_id = :owner_id:', ['owner_id' => $where['owner_id']]);
        }

        if (!empty($where['published'])) {
            if (is_array($where['published'])) {
                $builder->inWhere('published', $where['published']);
            } else {
                $builder->andWhere('published = :published:', ['published' => $where['published']]);
            }
        }

        if (!empty($where['create_time'][0]) && !empty($where['create_time'][1])) {
            $startTime = strtotime($where['create_time'][0]);
            $endTime = strtotime($where['create_time'][1]);
            $builder->betweenWhere('create_time', $startTime, $endTime);
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
            case 'oldest':
                $orderBy = 'id ASC';
                break;
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
        return ReviewModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param int $courseId
     * @param int $userId
     * @return ReviewModel|Model|bool
     */
    public function findReview($courseId, $userId)
    {
        return ReviewModel::findFirst([
            'conditions' => 'course_id = :course_id: AND owner_id = :owner_id:',
            'bind' => ['course_id' => $courseId, 'owner_id' => $userId],
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|ReviewModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return ReviewModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    public function countReviews()
    {
        return (int)ReviewModel::count([
            'conditions' => 'published = :published: AND deleted = 0',
            'bind' => ['published' => ReviewModel::PUBLISH_APPROVED],
        ]);
    }

    public function countLikes($reviewId)
    {
        return ReviewLikeModel::count([
            'conditions' => 'review_id = :review_id: AND deleted = 0',
            'bind' => ['review_id' => $reviewId],
        ]);
    }

}
