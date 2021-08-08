<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Course as CourseModel;
use App\Models\CourseUser as CourseUserModel;

class TeacherCourse extends Repository
{

    public function paginate($userId, $page = 1, $limit = 15)
    {

        $builder = $this->modelsManager->createBuilder()
            ->columns('course.*')
            ->addFrom(CourseModel::class, 'course')
            ->join(CourseUserModel::class, 'course.id = cu.course_id', 'cu')
            ->where('cu.user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('cu.role_type = :role_type:', ['role_type' => CourseUserModel::ROLE_TEACHER])
            ->andWhere('course.published = 1')
            ->andWhere('course.deleted = 0')
            ->orderBy('cu.id DESC');

        $pager = new PagerQueryBuilder([
            'builder' => $builder,
            'page' => $page,
            'limit' => $limit,
        ]);

        return $pager->paginate();
    }

}
