<?php

namespace App\Services\Frontend\My;

use App\Builders\CourseFavoriteList as CourseFavoriteListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\CourseFavorite as CourseFavoriteRepo;
use App\Services\Frontend\Service;
use App\Services\Frontend\UserTrait;

class FavoriteList extends Service
{

    use UserTrait;

    public function handle()
    {
        $user = $this->getLoginUser();

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['user_id'] = $user->id;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $favoriteRepo = new CourseFavoriteRepo();

        $pager = $favoriteRepo->paginate($params, $sort, $page, $limit);

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

            $course = $courses[$relation['course_id']] ?? new \stdClass();

            $items[] = $course;
        }

        $pager->items = $items;

        return $pager;
    }

}
