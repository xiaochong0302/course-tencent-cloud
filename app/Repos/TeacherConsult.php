<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Consult as ConsultModel;
use App\Models\Course as CourseModel;

class TeacherConsult extends Repository
{

    public function paginate($where, $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder()
            ->columns('consult.*')
            ->addFrom(ConsultModel::class, 'consult')
            ->join(CourseModel::class, 'consult.course_id = course.id', 'course')
            ->where('1 = 1');

        if (!empty($where['teacher_id'])) {
            $builder->andWhere('course.teacher_id = :teacher_id:', ['teacher_id' => $where['teacher_id']]);
            $builder->andWhere('course.published = 1');
            $builder->andWhere('course.deleted = 0');
        }

        if (isset($where['replied'])) {
            if ($where['replied'] == 1) {
                $builder->andWhere('consult.reply_time > 0');
            } else {
                $builder->andWhere('consult.reply_time = 0');
            }
        }

        $builder->andWhere('consult.published = :published:', ['published' => ConsultModel::PUBLISH_APPROVED]);
        $builder->andWhere('consult.deleted = 0');

        switch ($sort) {
            case 'oldest':
                $orderBy = 'consult.id ASC';
                break;
            default:
                $orderBy = 'consult.id DESC';
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

}
