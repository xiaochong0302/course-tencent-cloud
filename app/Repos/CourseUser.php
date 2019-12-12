<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\ChapterUser as ChapterUserModel;
use App\Models\CourseUser as CourseUserModel;

class CourseUser extends Repository
{

    /**
     * @param integer $courseId
     * @param integer $userId
     * @return CourseUserModel
     */
    public function findCourseUser($courseId, $userId)
    {
        $result = CourseUserModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('user_id = :user_id:', ['user_id' => $userId])
            ->execute()
            ->getFirst();

        return $result;
    }

    /**
     * @param integer $courseId
     * @param integer $userId
     * @return CourseUserModel
     */
    public function findCourseTeacher($courseId, $userId)
    {
        $roleType = CourseUserModel::ROLE_TEACHER;

        $result = CourseUserModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('role_type = :role_type:', ['role_type' => $roleType])
            ->execute()
            ->getFirst();

        return $result;
    }

    /**
     * @param integer $courseId
     * @param integer $userId
     * @return CourseUserModel
     */
    public function findCourseStudent($courseId, $userId)
    {
        $roleType = CourseUserModel::ROLE_STUDENT;

        $result = CourseUserModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('role_type = :role_type:', ['role_type' => $roleType])
            ->execute()
            ->getFirst();

        return $result;
    }

    public function countFinishedChapters($courseId, $userId)
    {
        $count = ChapterUserModel::count([
            'conditions' => 'course_id = :course_id: AND user_id = :user_id: AND finished = 1',
            'bind' => ['course_id' => $courseId, 'user_id' => $userId]
        ]);

        return (int)$count;
    }

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(CourseUserModel::class);

        $builder->where('1 = 1');

        if (!empty($where['course_id'])) {
            $builder->andWhere('course_id = :course_id:', ['course_id' => $where['course_id']]);
        }

        if (!empty($where['user_id'])) {
            $builder->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
        }

        if (!empty($where['role_type'])) {
            $builder->andWhere('role_type = :role_type:', ['role_type' => $where['role_type']]);
        }

        if (!empty($where['source_type'])) {
            $builder->andWhere('source_type = :source_type:', ['source_type' => $where['source_type']]);
        }

        if (isset($where['locked'])) {
            $builder->andWhere('locked = :locked:', ['locked' => $where['locked']]);
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

        return $pager->getPaginate();
    }

}
