<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Chapter as ChapterModel;
use App\Models\ChapterUser as ChapterUserModel;
use App\Models\Course as CourseModel;
use App\Models\CourseRating as CourseRatingModel;
use App\Models\CourseRelated as CourseRelatedModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\Review as ReviewModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\Model\Row;
use Phalcon\Paginator\RepositoryInterface as PagerRepoInterface;

class Course extends Repository
{

    /**
     * @param array $where
     * @param string $sort
     * @param int $page
     * @param int $limit
     * @return PagerRepoInterface
     */
    public function paginate(array $where = [], string $sort = 'latest', int $page = 1, int $limit = 15): PagerRepoInterface
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(CourseModel::class);

        $builder->where('1 = 1');

        if (!empty($where['id'])) {
            if (is_array($where['id'])) {
                $builder->inWhere('id', $where['id']);
            } else {
                $builder->andWhere('id = :id:', ['id' => $where['id']]);
            }
        }

        if (!empty($where['category_id'])) {
            if (is_array($where['category_id'])) {
                $builder->inWhere('category_id', $where['category_id']);
            } else {
                $builder->andWhere('category_id = :category_id:', ['category_id' => $where['category_id']]);
            }
        }

        if (!empty($where['teacher_id'])) {
            if (is_array($where['teacher_id'])) {
                $builder->inWhere('teacher_id', $where['teacher_id']);
            } else {
                $builder->andWhere('teacher_id = :teacher_id:', ['teacher_id' => $where['teacher_id']]);
            }
        }

        if (!empty($where['title'])) {
            $builder->andWhere('title LIKE :title:', ['title' => "%{$where['title']}%"]);
        }

        if (!empty($where['model'])) {
            if (is_array($where['model'])) {
                $builder->inWhere('model', $where['model']);
            } else {
                $builder->andWhere('model = :model:', ['model' => $where['model']]);
            }
        }

        if (!empty($where['level'])) {
            if (is_array($where['level'])) {
                $builder->inWhere('level', $where['level']);
            } else {
                $builder->andWhere('level = :level:', ['level' => $where['level']]);
            }
        }

        if (!empty($where['create_time'][0]) && !empty($where['create_time'][1])) {
            $startTime = strtotime($where['create_time'][0]);
            $endTime = strtotime($where['create_time'][1]);
            $builder->betweenWhere('create_time', $startTime, $endTime);
        }

        if (isset($where['free'])) {
            if ($where['free'] == 1) {
                $builder->andWhere('market_price = 0');
            } else {
                $builder->andWhere('market_price > 0');
            }
        }

        if (isset($where['featured'])) {
            $builder->andWhere('featured = :featured:', ['featured' => $where['featured']]);
        }

        if (isset($where['published'])) {
            $builder->andWhere('published = :published:', ['published' => $where['published']]);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        if ($sort == 'free') {
            $builder->andWhere('market_price = 0');
        } elseif ($sort == 'featured') {
            $builder->andWhere('featured = 1');
        } elseif ($sort == 'vip_discount') {
            $builder->andWhere('vip_price < market_price');
            $builder->andWhere('vip_price > 0');
        } elseif ($sort == 'vip_free') {
            $builder->andWhere('market_price > 0');
            $builder->andWhere('vip_price = 0');
        }

        $orderBy = match ($sort) {
            'score' => 'score DESC, id DESC',
            'rating' => 'rating DESC, id DESC',
            'popular' => 'user_count DESC, id DESC',
            'oldest' => 'id ASC',
            default => 'id DESC',
        };

        $builder->orderBy($orderBy);

        $pager = new PagerQueryBuilder([
            'builder' => $builder,
            'page' => $page,
            'limit' => $limit,
        ]);

        return $pager->paginate();
    }

    /**
     * @param array $where
     * @param string $sort
     * @param int $limit
     * @return ResultsetInterface|Resultset|CourseModel[]
     */
    public function findAll(array $where = [], string $sort = 'latest', int $limit = 10000)
    {
        /**
         * 一个偷懒的实现，适用于中小体量数据
         */
        $paginate = $this->paginate($where, $sort, 1, $limit);

        return $paginate->getItems();
    }

    /**
     * @param int $id
     * @return CourseModel|Row|null
     */
    public function findById(int $id)
    {
        return CourseModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param string $title
     * @return CourseModel|Row|null
     */
    public function findByTitle(string $title)
    {
        return CourseModel::findFirst([
            'conditions' => 'title = :title:',
            'bind' => ['title' => $title],
            'order' => 'id DESC',
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|CourseModel[]
     */
    public function findByIds(array $ids, array|string $columns = '*')
    {
        return CourseModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param array $ids
     * @return ResultsetInterface|Resultset|CourseModel[]
     */
    public function findShallowCourseByIds(array $ids)
    {
        return CourseModel::query()
            ->columns(['id', 'title', 'cover', 'market_price', 'user_count'])
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param int $courseId
     * @return CourseRatingModel|Row|null
     */
    public function findCourseRating(int $courseId)
    {
        return CourseRatingModel::findFirst([
            'conditions' => 'course_id = :course_id:',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    /**
     * @param int $courseId
     * @return ResultsetInterface|Resultset|CourseModel[]
     */
    public function findRelatedCourses(int $courseId)
    {
        return $this->modelsManager->createBuilder()
            ->columns('c.*')
            ->addFrom(CourseModel::class, 'c')
            ->join(CourseRelatedModel::class, 'c.id = cr.related_id', 'cr')
            ->where('cr.course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('c.published = 1')
            ->andWhere('c.deleted = 0')
            ->getQuery()->execute();
    }

    /**
     * @param int $courseId
     * @return ResultsetInterface|Resultset|ChapterModel[]
     */
    public function findChapters(int $courseId)
    {
        return ChapterModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('deleted = 0')
            ->execute();
    }

    /**
     * @param int $courseId
     * @return ResultsetInterface|Resultset|ChapterModel[]
     */
    public function findLessons(int $courseId)
    {
        return ChapterModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('parent_id > 0')
            ->andWhere('deleted = 0')
            ->execute();
    }

    /**
     * @param int $courseId
     * @param int $userId
     * @param int $planId
     * @return ResultsetInterface|Resultset|ChapterUserModel[]
     */
    public function findUserLearnings(int $courseId, int $userId, int $planId)
    {
        return ChapterUserModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('plan_id = :plan_id:', ['plan_id' => $planId])
            ->execute();
    }

    /**
     * @param int $courseId
     * @param int $userId
     * @return ChapterUserModel|Row|null
     */
    public function findLastChapterUser(int $courseId, int $userId)
    {
        return ChapterUserModel::findFirst([
            'conditions' => 'course_id = ?1 AND user_id = ?2',
            'bind' => [1 => $courseId, 2 => $userId],
            'order' => 'update_time DESC',
        ]);
    }

    /**
     * @return int
     */
    public function countCourses(): int
    {
        return (int)CourseModel::count([
            'conditions' => 'published = 1 AND deleted = 0',
        ]);
    }

    /**
     * @param int $courseId
     * @return int
     */
    public function countLessons(int $courseId): int
    {
        return (int)ChapterModel::count([
            'conditions' => 'course_id = :course_id: AND parent_id > 0 AND deleted = 0',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    /**
     * @param int $courseId
     * @return int
     */
    public function countUsers(int $courseId): int
    {
        return (int)CourseUserModel::count([
            'conditions' => 'course_id = :course_id: AND deleted = 0',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    /**
     * @param int $courseId
     * @return int
     */
    public function countReviews(int $courseId): int
    {
        return (int)ReviewModel::count([
            'conditions' => 'course_id = ?1 AND published = ?2 AND deleted = 0',
            'bind' => [1 => $courseId, 2 => ReviewModel::PUBLISH_APPROVED],
        ]);
    }

}
