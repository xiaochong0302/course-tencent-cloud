<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\User;

use App\Builders\CourseUserList as CourseUserListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\CourseUser as CourseUserModel;
use App\Repos\CourseUser as CourseUserRepo;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\UserTrait;

class CourseList extends LogicService
{

    use UserTrait;

    public function handle($id)
    {
        $user = $this->checkUserCache($id);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['user_id'] = $user->id;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $repo = new CourseUserRepo();

        $pager = $repo->paginate($params, $sort, $page, $limit);

        return $this->handlePager($pager);
    }

    protected function handlePager($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $builder = new CourseUserListBuilder();

        $relations = $pager->items->toArray();

        $courses = $builder->getCourses($relations);

        $items = [];

        foreach ($relations as $relation) {

            $course = $courses[$relation['course_id']] ?? new \stdClass();

            $items[] = [
                'progress' => $relation['progress'],
                'duration' => $relation['duration'],
                'reviewed' => $relation['reviewed'],
                'source_type' => $relation['source_type'],
                'expiry_time' => $relation['expiry_time'],
                'create_time' => $relation['create_time'],
                'course' => $course,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
