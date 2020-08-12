<?php

namespace App\Services\Frontend\My;

use App\Builders\ReviewList as ReviewListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Review as ReviewRepo;
use App\Services\Frontend\Service as FrontendService;

class ReviewList extends FrontendService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['owner_id'] = $user->id;
        $params['published'] = 1;

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

            $course = $courses[$review['course_id']] ?? new \stdClass();

            $items[] = [
                'id' => $review['id'],
                'content' => $review['content'],
                'reply' => $review['reply'],
                'rating' => $review['rating'],
                'rating1' => $review['rating1'],
                'rating2' => $review['rating2'],
                'rating3' => $review['rating3'],
                'like_count' => $review['like_count'],
                'create_time' => $review['create_time'],
                'update_time' => $review['update_time'],
                'course' => $course,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
