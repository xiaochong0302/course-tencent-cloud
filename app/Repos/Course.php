<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Category as CategoryModel;
use App\Models\Chapter as ChapterModel;
use App\Models\ChapterUser as ChapterUserModel;
use App\Models\Comment as CommentModel;
use App\Models\Consult as ConsultModel;
use App\Models\ConsultVote as ConsultVoteModel;
use App\Models\Course as CourseModel;
use App\Models\CourseCategory as CourseCategoryModel;
use App\Models\CourseFavorite as CourseFavoriteModel;
use App\Models\CoursePackage as CoursePackageModel;
use App\Models\CourseRelated as CourseRelatedModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\Package as PackageModel;
use App\Models\Review as ReviewModel;
use App\Models\ReviewVote as ReviewVoteModel;
use App\Models\User as UserModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Course extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        if (!empty($where['category_id'])) {
            $builder->addFrom(CourseModel::class, 'c');
            $builder->join(CourseCategoryModel::class, 'c.id = cc.course_id', 'cc');
            if (is_array($where['category_id'])) {
                $builder->inWhere('cc.category_id', $where['category_id']);
            } else {
                $builder->where('cc.category_id = :category_id:', ['category_id' => $where['category_id']]);
            }
        } else {
            $builder->addFrom(CourseModel::class, 'c');
            $builder->where('1 = 1');
        }

        if (!empty($where['id'])) {
            if (is_array($where['id'])) {
                $builder->inWhere('c.id', $where['id']);
            } else {
                $builder->andWhere('c.id = :id:', ['id' => $where['id']]);
            }
        }

        if (!empty($where['title'])) {
            $builder->andWhere('c.title LIKE :title:', ['title' => "%{$where['title']}%"]);
        }

        if (!empty($where['model'])) {
            $builder->andWhere('c.model = :model:', ['model' => $where['model']]);
        }

        if (!empty($where['level'])) {
            $builder->andWhere('c.level = :level:', ['level' => $where['level']]);
        }

        if (isset($where['free'])) {
            if ($where['free'] == 1) {
                $builder->andWhere('c.market_price = 0');
            } else {
                $builder->andWhere('c.market_price > 0');
            }
        }

        if (isset($where['published'])) {
            $builder->andWhere('c.published = :published:', ['published' => $where['published']]);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('c.deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        switch ($sort) {
            case 'rating':
                $orderBy = 'c.rating DESC';
                break;
            case 'score':
                $orderBy = 'c.score DESC';
                break;
            default:
                $orderBy = 'c.id DESC';
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
     * @return CourseModel|Model|bool
     */
    public function findById($id)
    {
        return CourseModel::findFirst($id);
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
     * @return ResultsetInterface|Resultset|UserModel[]
     */
    public function findTeachers($courseId)
    {
        $roleType = CourseUserModel::ROLE_TEACHER;

        return $this->modelsManager->createBuilder()
            ->columns('u.*')
            ->addFrom(UserModel::class, 'u')
            ->join(CourseUserModel::class, 'u.id = cu.user_id', 'cu')
            ->where('cu.course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('cu.role_type = :role_type:', ['role_type' => $roleType])
            ->andWhere('u.deleted = 0')
            ->getQuery()->execute();
    }

    /**
     * @param int $courseId
     * @return ResultsetInterface|Resultset|CategoryModel[]
     */
    public function findCategories($courseId)
    {
        return $this->modelsManager->createBuilder()
            ->columns('c.*')
            ->addFrom(CategoryModel::class, 'c')
            ->join(CourseCategoryModel::class, 'c.id = cc.category_id', 'cc')
            ->where('cc.course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('c.deleted = 0')
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
     * @param int $userId
     * @return ResultsetInterface|Resultset|ChapterUserModel[]
     */
    public function findUserLearnings($courseId, $userId)
    {
        return ChapterUserModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('deleted = 0')
            ->execute();
    }

    /**
     * @param int $courseId
     * @param int $userId
     * @return ResultsetInterface|Resultset|ChapterUserModel[]
     */
    public function findConsumedUserLearnings($courseId, $userId)
    {
        return ChapterUserModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('consumed = 1 AND deleted = 0')
            ->execute();
    }

    /**
     * @param int $courseId
     * @param int $userId
     * @return ResultsetInterface|Resultset|ConsultVoteModel[]
     */
    public function findUserConsultVotes($courseId, $userId)
    {
        return $this->modelsManager->createBuilder()
            ->columns('cv.*')
            ->addFrom(ConsultModel::class, 'c')
            ->join(ConsultVoteModel::class, 'c.id = cv.consult_id', 'cv')
            ->where('c.course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('cv.user_id = :user_id:', ['user_id' => $userId])
            ->getQuery()->execute();
    }

    /**
     * @param int $courseId
     * @param int $userId
     * @return ResultsetInterface|Resultset|ReviewVoteModel[]
     */
    public function findUserReviewVotes($courseId, $userId)
    {
        return $this->modelsManager->createBuilder()
            ->columns('rv.*')
            ->addFrom(ReviewModel::class, 'r')
            ->join(ReviewVoteModel::class, 'r.id = rv.review_id', 'rv')
            ->where('r.course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('rv.user_id = :user_id:', ['user_id' => $userId])
            ->getQuery()->execute();
    }

    public function countLessons($courseId)
    {
        return ChapterModel::count([
            'conditions' => 'course_id = :course_id: AND parent_id > 0 AND deleted = 0',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    public function countUsers($courseId)
    {
        return CourseUserModel::count([
            'conditions' => 'course_id = :course_id: AND deleted = 0',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    public function countConsults($courseId)
    {
        return ConsultModel::count([
            'conditions' => 'course_id = :course_id: AND deleted = 0',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    public function countReviews($courseId)
    {
        return ReviewModel::count([
            'conditions' => 'course_id = :course_id: AND deleted = 0',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    public function countComments($courseId)
    {
        return CommentModel::count([
            'conditions' => 'course_id = :course_id: AND deleted = 0',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    public function countFavorites($courseId)
    {
        return CourseFavoriteModel::count([
            'conditions' => 'course_id = :course_id: AND deleted = 0',
            'bind' => ['course_id' => $courseId],
        ]);
    }

}
