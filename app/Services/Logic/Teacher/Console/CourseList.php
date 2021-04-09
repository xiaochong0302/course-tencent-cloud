<?php

namespace App\Services\Logic\Teacher\Console;

use App\Builders\CourseUserList as CourseUserListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\CourseUser as CourseUserModel;
use App\Repos\CourseUser as CourseUserRepo;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\UserTrait;

class CourseList extends LogicService
{

    use UserTrait;

    public function handle()
    {
        $user = $this->getLoginUser();

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['user_id'] = $user->id;
        $params['role_type'] = CourseUserModel::ROLE_TEACHER;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $courseUserRepo = new CourseUserRepo();

        $pager = $courseUserRepo->paginate($params, $sort, $page, $limit);

        return $this->handleCourses($pager);
    }

    protected function handleCourses($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $builder = new CourseUserListBuilder();

        $relations = $pager->items->toArray();

        $courses = $builder->getCourses($relations);

        $pager->items = $courses;

        return $pager;
    }

}
