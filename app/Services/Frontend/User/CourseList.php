<?php

namespace App\Services\Frontend\User;

use App\Builders\CourseUserList as CourseUserListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\CourseUser as CourseUserModel;
use App\Repos\CourseUser as CourseUserRepo;
use App\Services\Frontend\Service;
use App\Services\Frontend\UserTrait;

class CourseList extends Service
{

    use UserTrait;

    public function handle($id)
    {
        $user = $this->checkUser($id);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['user_id'] = $user->id;
        $params['role_type'] = CourseUserModel::ROLE_STUDENT;
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

        $items = [];

        foreach ($relations as $relation) {

            $course = $courses[$relation['course_id']] ?? new \stdClass();

            $items = [
                'course' => $course,
                'progress' => $relation['progress'],
                'duration' => $relation['duration'],
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
