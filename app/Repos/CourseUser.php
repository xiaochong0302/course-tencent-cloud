<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\CourseUser as CourseUserModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class CourseUser extends Repository
{

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

        return $pager->paginate();
    }

    /**
     * @param int $id
     * @return CourseUserModel|Model|bool
     */
    public function findById($id)
    {
        return CourseUserModel::findFirst($id);
    }

    /**
     * @param int $courseId
     * @param int $userId
     * @return CourseUserModel|Model|bool
     */
    public function findCourseUser($courseId, $userId)
    {
        return CourseUserModel::findFirst([
            'conditions' => 'course_id = ?1 AND user_id = ?2 AND deleted = 0',
            'bind' => [1 => $courseId, 2 => $userId],
            'order' => 'id DESC',
        ]);
    }

    /**
     * @param int $courseId
     * @param int $userId
     * @return CourseUserModel|Model|bool
     */
    public function findCourseTeacher($courseId, $userId)
    {
        $roleType = CourseUserModel::ROLE_TEACHER;

        return $this->findRoleCourseUser($courseId, $userId, $roleType);
    }

    /**
     * @param int $courseId
     * @param int $userId
     * @return CourseUserModel|Model|bool
     */
    public function findCourseStudent($courseId, $userId)
    {
        $roleType = CourseUserModel::ROLE_STUDENT;

        return $this->findRoleCourseUser($courseId, $userId, $roleType);
    }

    /**
     * @param int $courseId
     * @param int $userId
     * @param string $roleType
     * @return CourseUserModel|Model|bool
     */
    protected function findRoleCourseUser($courseId, $userId, $roleType)
    {
        return CourseUserModel::findFirst([
            'conditions' => 'course_id = ?1 AND user_id = ?2 AND role_type = ?3 AND deleted = 0',
            'bind' => [1 => $courseId, 2 => $userId, 3 => $roleType],
            'order' => 'id DESC',
        ]);
    }

    /**
     * @param array $teacherIds
     * @return ResultsetInterface|Resultset|CourseUserModel[]
     */
    public function findByTeacherIds(array $teacherIds)
    {
        $roleType = CourseUserModel::ROLE_TEACHER;

        return CourseUserModel::query()
            ->inWhere('user_id', $teacherIds)
            ->andWhere('role_type = :role_type:', ['role_type' => $roleType])
            ->execute();
    }

}
