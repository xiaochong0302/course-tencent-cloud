<?php

namespace App\Services\Frontend;

use App\Builders\ReviewList as ReviewListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Review as ReviewModel;
use App\Repos\Review as ReviewRepo;
use App\Validators\Review as ReviewValidator;

class CourseReview extends Service
{

    use CourseTrait;

    public function getReviews($id)
    {
        $course = $this->checkCourseCache($id);

        $pagerQuery = new PagerQuery();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $params = [
            'course_id' => $course->id,
            'published' => 1,
            'deleted' => 0,
        ];

        $reviewRepo = new ReviewRepo();

        $pager = $reviewRepo->paginate($params, $sort, $page, $limit);

        return $this->handleReviews($pager);
    }

    public function saveReview($id)
    {
        $post = $this->request->getPost();

        $course = $this->checkCourse($id);

        $user = $this->getLoginUser();

        $validator = new ReviewValidator();

        $rating = $validator->checkRating($post['rating']);
        $content = $validator->checkContent($post['content']);

        $reviewRepo = new ReviewRepo();

        $review = $reviewRepo->findReview($course->id, $user->id);

        if (!$review) {
            $review = new ReviewModel();
            $review->course_id = $course->id;
            $review->user_id = $user->id;
            $review->rating = $rating;
            $review->content = $content;
            $review->create();

            $course->review_count += 1;
            $course->update();
        } else {
            $review->rating = $rating;
            $review->content = $content;
            $review->update();
        }
    }

    protected function handleReviews($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new ReviewListBuilder();

            $pipeA = $pager->items->toArray();
            $pipeB = $builder->handleUsers($pipeA);

            $pager->items = $pipeB;
        }

        return $pager;
    }

}
