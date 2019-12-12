<?php

namespace App\Http\Admin\Services;

use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Course as CourseRepo;

class XmCourse extends Service
{

    public function getAllCourses()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $courseRepo = new CourseRepo();

        $pager = $courseRepo->paginate($params, $sort, $page, $limit);

        return $pager;
    }

    public function getPaidCourses()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['free'] = 0;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $courseRepo = new CourseRepo();

        $pager = $courseRepo->paginate($params, $sort, $page, $limit);

        return $pager;
    }

}
