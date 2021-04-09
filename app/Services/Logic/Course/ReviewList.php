<?php

namespace App\Services\Logic\Course;

use App\Builders\ReviewList as ReviewListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Review as ReviewRepo;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service as LogicService;

class ReviewList extends LogicService
{

    use CourseTrait;

    public function handle($id)
    {
        $course = $this->checkCourse($id);

        $pagerQuery = new PagerQuery();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $params = [
            'course_id' => $course->id,
            'published' => 1,
        ];

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

        $users = $builder->getUsers($reviews);

        $items = [];

        foreach ($reviews as $review) {

            $owner = $users[$review['owner_id']] ?? new \stdClass();

            $items[] = [
                'id' => $review['id'],
                'rating' => $review['rating'],
                'content' => $review['content'],
                'like_count' => $review['like_count'],
                'create_time' => $review['create_time'],
                'owner' => $owner,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
