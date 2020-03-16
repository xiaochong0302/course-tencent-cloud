<?php

namespace App\Services\Frontend;

use App\Builders\CourseFavoriteList as CourseFavoriteListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\CourseFavorite as CourseFavoriteRepo;

class MyFavoriteList extends Service
{

    use UserTrait;

    public function getCourses($id)
    {
        $user = $this->checkUser($id);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['user_id'] = $user->id;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $courseFavoriteRepo = new CourseFavoriteRepo();

        $pager = $courseFavoriteRepo->paginate($params, $sort, $page, $limit);

        return $this->handleCourses($pager);
    }

    protected function handleCourses($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $builder = new CourseFavoriteListBuilder();

        $relations = $pager->items->toArray();

        $courses = $builder->getCourses($relations);

        $items = [];

        foreach ($relations as $relation) {

            $course = $courses[$relation['course_id']] ?? [];

            $items[] = $course;
        }

        $pager->items = $items;

        return $pager;
    }

}
