<?php

namespace App\Services\Frontend\My;

use App\Builders\ReviewList as ReviewListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Review as ReviewRepo;
use App\Services\Frontend\Service;
use App\Services\Frontend\UserTrait;

class ReviewList extends Service
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

        $reviewRepo = new ReviewRepo();

        $pager = $reviewRepo->paginate($params, $sort, $page, $limit);

        return $this->handleReviews($pager);
    }

    protected function handleReviews($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $builder = new ReviewListBuilder();

        $reviews = $pager->items->toArray();

        $courses = $builder->getCourses($reviews);

        $items = [];

        foreach ($reviews as $review) {

            $course = $courses[$review['course_id']] ?? [];

            $items[] = [
                'id' => $review['id'],
                'question' => $review['question'],
                'answer' => $review['answer'],
                'agree_count' => $review['agree_count'],
                'oppose_count' => $review['oppose_count'],
                'create_time' => $review['create_time'],
                'update_time' => $review['update_time'],
                'course' => $course,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
