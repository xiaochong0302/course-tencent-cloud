<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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

        if (!empty($where['plan_id'])) {
            $builder->andWhere('plan_id = :plan_id:', ['plan_id' => $where['plan_id']]);
        }

        if (!empty($where['source_type'])) {
            $builder->andWhere('source_type = :source_type:', ['source_type' => $where['source_type']]);
        }

        if (!empty($where['create_time'][0]) && !empty($where['create_time'][1])) {
            $startTime = strtotime($where['create_time'][0]);
            $endTime = strtotime($where['create_time'][1]);
            $builder->betweenWhere('create_time', $startTime, $endTime);
        }

        if (!empty($where['expiry_time'][0]) && !empty($where['expiry_time'][1])) {
            $startTime = strtotime($where['expiry_time'][0]);
            $endTime = strtotime($where['expiry_time'][1]);
            $builder->betweenWhere('expiry_time', $startTime, $endTime);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
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
     * @param int $planId
     * @return CourseUserModel|Model|bool
     */
    public function findPlanCourseUser($courseId, $userId, $planId)
    {
        return CourseUserModel::findFirst([
            'conditions' => 'course_id = ?1 AND user_id = ?2 AND plan_id = ?3 AND deleted = 0',
            'bind' => [1 => $courseId, 2 => $userId, 3 => $planId],
        ]);
    }

    /**
     * @param int $courseId
     * @return ResultsetInterface|Resultset|CourseUserModel[]
     */
    public function findByCourseId($courseId)
    {
        return CourseUserModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('deleted = 0')
            ->execute();
    }

    /**
     * @param int $courseId
     * @param int $userId
     * @return ResultsetInterface|Resultset|CourseUserModel[]
     */
    public function findByCourseAndUserId($courseId, $userId)
    {
        return CourseUserModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('user_id = :user_id:', ['user_id' => $userId])
            ->execute();
    }

}
