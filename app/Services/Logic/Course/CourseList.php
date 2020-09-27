<?php

namespace App\Services\Logic\Course;

use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Course as CourseRepo;
use App\Services\Category as CategoryService;
use App\Services\Logic\Service;

class CourseList extends Service
{

    public function handle()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        /**
         * tc => top_category
         * sc => sub_category
         */
        if (!empty($params['sc'])) {

            $params['category_id'] = $params['sc'];

        } elseif (!empty($params['tc'])) {

            $categoryService = new CategoryService();

            $childCategoryIds = $categoryService->getChildCategoryIds($params['tc']);

            $params['category_id'] = $childCategoryIds;
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
        if ($pager->total_items == 0) {
            return $pager;
        }

        $courses = $pager->items->toArray();

        $items = [];

        $baseUrl = kg_cos_url();

        foreach ($courses as $course) {

            $course['cover'] = $baseUrl . $course['cover'];

            $items[] = [
                'id' => $course['id'],
                'title' => $course['title'],
                'cover' => $course['cover'],
                'market_price' => (float)$course['market_price'],
                'vip_price' => (float)$course['vip_price'],
                'rating' => (float)$course['rating'],
                'model' => $course['model'],
                'level' => $course['level'],
                'user_count' => $course['user_count'],
                'lesson_count' => $course['lesson_count'],
                'review_count' => $course['review_count'],
                'favorite_count' => $course['favorite_count'],
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
