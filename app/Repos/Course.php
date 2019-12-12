<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Category as CategoryModel;
use App\Models\Chapter as ChapterModel;
use App\Models\ChapterUser as ChapterUserModel;
use App\Models\Course as CourseModel;
use App\Models\CourseCategory as CourseCategoryModel;
use App\Models\CourseRelated as CourseRelatedModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\Review as ReviewModel;
use App\Models\User as UserModel;

class Course extends Repository
{

    /**
     * @param integer $id
     * @return CourseModel
     */
    public function findById($id)
    {
        $result = CourseModel::findFirstById($id);

        return $result;
    }

    public function findByIds($ids, $columns = '*')
    {
        $result = CourseModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

    public function findTeachers($courseId)
    {
        $roleType = CourseUserModel::ROLE_TEACHER;

        $result = $this->modelsManager->createBuilder()
            ->columns('u.*')
            ->addFrom(UserModel::class, 'u')
            ->join(CourseUserModel::class, 'u.id = cu.user_id', 'cu')
            ->where('cu.course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('cu.role_type = :role_type:', ['role_type' => $roleType])
            ->andWhere('u.locked = 0')
            ->getQuery()->execute();

        return $result;
    }

    public function findCategories($courseId)
    {
        $result = $this->modelsManager->createBuilder()
            ->columns('c.*')
            ->addFrom(CategoryModel::class, 'c')
            ->join(CourseCategoryModel::class, 'c.id = cc.category_id', 'cc')
            ->where('cc.course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('c.deleted = 0')
            ->getQuery()->execute();

        return $result;
    }

    public function findRelatedCourses($courseId)
    {
        $result = $this->modelsManager->createBuilder()
            ->columns('c.*')
            ->addFrom(CourseModel::class, 'c')
            ->join(CourseRelatedModel::class, 'c.id = cr.related_id', 'cr')
            ->where('cr.course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('c.deleted = 0')
            ->getQuery()->execute();

        return $result;
    }

    public function findChapters($courseId)
    {
        $result = ChapterModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('deleted = 0')
            ->execute();

        return $result;
    }

    public function findLessons($courseId)
    {
        $result = ChapterModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('parent_id > 0')
            ->andWhere('deleted = 0')
            ->execute();

        return $result;
    }

    public function findUserLessons($courseId, $userId)
    {
        $result = ChapterUserModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('user_id = :user_id:', ['user_id' => $userId])
            ->execute();

        return $result;
    }

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
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

        if (!empty($where['user_id'])) {
            $builder->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
        }

        if (!empty($where['title'])) {
            $builder->andWhere('title LIKE :title:', ['title' => '%' . $where['title'] . '%']);
        }

        if (!empty($where['model'])) {
            $builder->andWhere('model = :model:', ['model' => $where['model']]);
        }

        if (!empty($where['level'])) {
            $builder->andWhere('level = :level:', ['level' => $where['level']]);
        }

        if (isset($where['free'])) {
            if ($where['free'] == 1) {
                $builder->andWhere('market_price = 0');
            } else {
                $builder->andWhere('market_price > 0');
            }
        }

        if (isset($where['published'])) {
            $builder->andWhere('published = :published:', ['published' => $where['published']]);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        switch ($sort) {
            case 'rating':
                $orderBy = 'rating DESC';
                break;
            case 'score':
                $orderBy = 'score DESC';
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

        return $pager->getPaginate();
    }

    public function countLessons($courseId)
    {
        $count = ChapterModel::count([
            'conditions' => 'course_id = :course_id: AND parent_id > 0 AND deleted = 0',
            'bind' => ['course_id' => $courseId],
        ]);

        return (int)$count;
    }

    public function countStudents($courseId)
    {
        $count = CourseUserModel::count([
            'conditions' => 'course_id = :course_id: AND deleted = 0',
            'bind' => ['course_id' => $courseId],
        ]);

        return (int)$count;
    }

    public function countReviews($courseId)
    {
        $count = ReviewModel::count([
            'conditions' => 'course_id = :course_id: AND deleted = 0',
            'bind' => ['course_id' => $courseId],
        ]);

        return (int)$count;
    }

}
