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
use App\Models\Consult as ConsultModel;
use App\Models\Course as CourseModel;
use App\Models\CourseFavorite as CourseFavoriteModel;
use App\Models\CoursePackage as CoursePackageModel;
use App\Models\CourseRating as CourseRatingModel;
use App\Models\CourseRelated as CourseRelatedModel;
use App\Models\CourseTag as CourseTagModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\Package as PackageModel;
use App\Models\Resource as ResourceModel;
use App\Models\Review as ReviewModel;
use App\Models\Tag as TagModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Course extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(CourseModel::class);

        $builder->where('1 = 1');

        $fakeId = false;

        if (!empty($where['tag_id'])) {
            $where['id'] = $this->getTagCourseIds($where['tag_id']);
            $fakeId = empty($where['id']);
        }

        /**
         * 构造空记录条件
         */
        if ($fakeId) $where['id'] = -999;

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

        if (!empty($where['title'])) {
            $builder->andWhere('title LIKE :title:', ['title' => "%{$where['title']}%"]);
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

        switch ($sort) {
            case 'score':
                $orderBy = 'score DESC, id DESC';
                break;
            case 'rating':
                $orderBy = 'rating DESC, id DESC';
                break;
            case 'popular':
                $orderBy = 'user_count DESC, id DESC';
                break;
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
     * @param array $where
     * @param string $sort
     * @return ResultsetInterface|Resultset|CourseModel[]
     */
    public function findAll($where = [], $sort = 'latest')
    {
        /**
         * 一个偷懒的实现，适用于中小体量数据
         */
        $paginate = $this->paginate($where, $sort, 1, 10000);

        return $paginate->items;
    }

    /**
     * @param int $id
     * @return CourseModel|Model|bool
     */
    public function findById($id)
    {
        return CourseModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|CourseModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return CourseModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param int $courseId
     * @return CourseRatingModel|Model|bool
     */
    public function findCourseRating($courseId)
    {
        return CourseRatingModel::findFirst([
            'conditions' => 'course_id = :course_id:',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    /**
     * @param int $courseId
     * @return ResultsetInterface|Resultset|TagModel[]
     */
    public function findTags($courseId)
    {
        return $this->modelsManager->createBuilder()
            ->columns('t.*')
            ->addFrom(TagModel::class, 't')
            ->join(CourseTagModel::class, 't.id = ct.tag_id', 'ct')
            ->where('ct.course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('t.published = 1')
            ->andWhere('t.deleted = 0')
            ->getQuery()->execute();
    }

    /**
     * @param int $courseId
     * @return ResultsetInterface|Resultset|PackageModel[]
     */
    public function findPackages($courseId)
    {
        return $this->modelsManager->createBuilder()
            ->columns('p.*')
            ->addFrom(PackageModel::class, 'p')
            ->join(CoursePackageModel::class, 'p.id = cp.package_id', 'cp')
            ->where('cp.course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('p.published = 1')
            ->andWhere('p.deleted = 0')
            ->getQuery()->execute();
    }

    /**
     * @param int $courseId
     * @return ResultsetInterface|Resultset|CourseModel[]
     */
    public function findRelatedCourses($courseId)
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
    public function findChapters($courseId)
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
    public function findLessons($courseId)
    {
        return ChapterModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('parent_id > 0')
            ->andWhere('deleted = 0')
            ->execute();
    }

    /**
     * @param int $courseId
     * @return ResultsetInterface|Resultset|ResourceModel[]
     */
    public function findResources($courseId)
    {
        return ResourceModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->execute();
    }

    /**
     * @param int $courseId
     * @param int $userId
     * @param int $planId
     * @return ResultsetInterface|Resultset|ChapterUserModel[]
     */
    public function findUserLearnings($courseId, $userId, $planId)
    {
        return ChapterUserModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('plan_id = :plan_id:', ['plan_id' => $planId])
            ->execute();
    }

    public function countCourses()
    {
        return (int)CourseModel::count([
            'conditions' => 'published = 1 AND deleted = 0',
        ]);
    }

    public function countLessons($courseId)
    {
        return (int)ChapterModel::count([
            'conditions' => 'course_id = :course_id: AND parent_id > 0 AND deleted = 0',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    public function countResources($courseId)
    {
        return (int)ResourceModel::count([
            'conditions' => 'course_id = :course_id:',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    public function countPackages($courseId)
    {
        return $this->findPackages($courseId)->count();
    }

    public function countUsers($courseId)
    {
        return (int)CourseUserModel::count([
            'conditions' => 'course_id = :course_id: AND deleted = 0',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    public function countConsults($courseId)
    {
        return (int)ConsultModel::count([
            'conditions' => 'course_id = ?1 AND published = ?2 AND deleted = 0',
            'bind' => [1 => $courseId, 2 => ConsultModel::PUBLISH_APPROVED],
        ]);
    }

    public function countReviews($courseId)
    {
        return (int)ReviewModel::count([
            'conditions' => 'course_id = ?1 AND published = ?2 AND deleted = 0',
            'bind' => [1 => $courseId, 2 => ReviewModel::PUBLISH_APPROVED],
        ]);
    }

    public function countFavorites($courseId)
    {
        return (int)CourseFavoriteModel::count([
            'conditions' => 'course_id = :course_id: AND deleted = 0',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    public function averageRating($courseId)
    {
        return (int)ReviewModel::average([
            'column' => 'rating',
            'conditions' => 'course_id = ?1 AND published = ?2 AND deleted = 0',
            'bind' => [1 => $courseId, 2 => ReviewModel::PUBLISH_APPROVED],
        ]);
    }

    protected function getTagCourseIds($tagId)
    {
        $tagIds = is_array($tagId) ? $tagId : [$tagId];

        $repo = new CourseTag();

        $rows = $repo->findByTagIds($tagIds);

        $result = [];

        if ($rows->count() > 0) {
            $result = kg_array_column($rows->toArray(), 'course_id');
        }

        return $result;
    }

}
