<?php

namespace App\Services\Frontend;

use App\Builders\CourseList as CourseListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Course as CourseRepo;
use App\Services\Category as CategoryService;

class CourseList extends Service
{

    public function getCourses()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        if (!empty($params['category_id'])) {
            $categoryService = new CategoryService();
            $childNodeIds = $categoryService->getChildNodeIds($params['category_id']);
            $params['category_id'] = $childNodeIds;
        }

        $params['published'] = 1;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $courseRepo = new CourseRepo();

        $pager = $courseRepo->paginate($params, $sort, $page, $limit);

        return $this->handleCourses($pager);
    }

    protected function handleCourses($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new CourseListBuilder();

            $pipeA = $pager->items->toArray();
            $pipeB = $builder->handleCourses($pipeA);
            $pipeC = $builder->handleCategories($pipeB);

            $pager->items = $pipeC;
        }

        return $pager;
    }

}
