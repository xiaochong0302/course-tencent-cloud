<?php

namespace App\Services\Frontend\Teaching;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Course as CourseModel;
use App\Models\CourseUser as CourseUserModel;
use App\Services\Frontend\Service as FrontendService;

class CourseList extends FrontendService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $pagerQuery = new PagerQuery();

        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $pager = $this->paginate($user->id, $page, $limit);

        return $this->handleCourses($pager);
    }

    protected function handleCourses($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $items = [];

        $baseUrl = kg_ss_url();

        foreach ($pager->items->toArray() as $course) {

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

    protected function paginate($userId, $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->columns('c.*');
        $builder->addFrom(CourseModel::class, 'c');
        $builder->join(CourseUserModel::class, 'c.id = cu.course_id', 'cu');
        $builder->where('cu.user_id = :user_id:', ['user_id' => $userId]);
        $builder->andWhere('cu.role_type = :role_type:', ['role_type' => CourseUserModel::ROLE_TEACHER]);
        $builder->andWhere('c.published = 1');
        $builder->orderBy('c.id DESC');

        $pager = new PagerQueryBuilder([
            'builder' => $builder,
            'page' => $page,
            'limit' => $limit,
        ]);

        return $pager->paginate();
    }

}
