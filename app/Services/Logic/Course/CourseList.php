<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Course;

use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Course as CourseRepo;
use App\Services\Category as CategoryService;
use App\Services\Logic\Service as LogicService;
use App\Validators\CourseQuery as CourseQueryValidator;

class CourseList extends LogicService
{

    public function handle()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params = $this->checkQueryParams($params);

        /**
         * tc => top_category
         * sc => sub_category
         */
        if (!empty($params['sc'])) {

            $params['category_id'] = $params['sc'];

        } elseif (!empty($params['tc'])) {

            $categoryService = new CategoryService();

            $childCategoryIds = $categoryService->getChildCategoryIds($params['tc']);

            $parentCategoryIds = [$params['tc']];

            $allCategoryIds = array_merge($parentCategoryIds, $childCategoryIds);

            $params['category_id'] = $allCategoryIds;
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

    public function handleCourses($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $courses = $pager->items->toArray();

        $items = [];

        $baseUrl = kg_cos_url();

        foreach ($courses as $course) {

            if ($course['fake_user_count'] > $course['user_count']) {
                $course['user_count'] = $course['fake_user_count'];
            }

            $course['cover'] = $baseUrl . $course['cover'];

            $items[] = [
                'id' => $course['id'],
                'title' => $course['title'],
                'cover' => $course['cover'],
                'model' => $course['model'],
                'level' => $course['level'],
                'rating' => round($course['rating'], 1),
                'market_price' => (float)$course['market_price'],
                'vip_price' => (float)$course['vip_price'],
                'user_count' => $course['user_count'],
                'lesson_count' => $course['lesson_count'],
                'review_count' => $course['review_count'],
                'favorite_count' => $course['favorite_count'],
            ];
        }

        $pager->items = $items;

        return $pager;
    }

    protected function checkQueryParams($params)
    {
        $validator = new CourseQueryValidator();

        $query = [];

        if (isset($params['teacher_id'])) {
            $user = $validator->checkUser($params['teacher_id']);
            $query['teacher_id'] = $user->id;
        }

        if (isset($params['tag_id'])) {
            $tag = $validator->checkTag($params['tag_id']);
            $query['tag_id'] = $tag->id;
        }

        if (isset($params['tc'])) {
            $category = $validator->checkCategory($params['tc']);
            $query['tc'] = $category->id;
        }

        if (isset($params['sc'])) {
            $category = $validator->checkCategory($params['sc']);
            $query['sc'] = $category->id;
        }

        if (isset($params['model'])) {
            $query['model'] = $validator->checkModel($params['model']);
        }

        if (isset($params['level'])) {
            $query['level'] = $validator->checkLevel($params['level']);
        }

        if (isset($params['sort'])) {
            $query['sort'] = $validator->checkSort($params['sort']);
        }

        return $query;
    }

}
