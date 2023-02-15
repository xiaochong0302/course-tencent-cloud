<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Consult as ConsultModel;
use App\Models\CourseUser as CourseUserModel;

class TeacherConsult extends Repository
{

    public function paginate($where, $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder()
            ->columns('c.*')
            ->addFrom(ConsultModel::class, 'c')
            ->join(CourseUserModel::class, 'c.course_id = cu.course_id', 'cu')
            ->where('1 = 1');

        if (!empty($where['user_id'])) {
            $builder->andWhere('cu.user_id = :user_id:', ['user_id' => $where['user_id']]);
            $builder->andWhere('cu.role_type = :role_type:', ['role_type' => CourseUserModel::ROLE_TEACHER]);
            $builder->andWhere('cu.deleted = 0');
        }

        if (isset($where['replied'])) {
            if ($where['replied'] == 1) {
                $builder->andWhere('c.reply_time > 0');
            } else {
                $builder->andWhere('c.reply_time = 0');
            }
        }

        $builder->andWhere('c.published = :published:', ['published' => ConsultModel::PUBLISH_APPROVED]);

        $builder->andWhere('c.deleted = 0');

        switch ($sort) {
            case 'oldest':
                $orderBy = 'c.id ASC';
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

}
