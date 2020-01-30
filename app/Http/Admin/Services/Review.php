<?php

namespace App\Http\Admin\Services;

use App\Builders\ReviewList as ReviewListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Course as CourseRepo;
use App\Repos\Review as ReviewRepo;
use App\Validators\Review as ReviewValidator;

class Review extends Service
{

    public function getReviews()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $reviewRepo = new ReviewRepo();

        $pager = $reviewRepo->paginate($params, $sort, $page, $limit);

        return $this->handleReviews($pager);
    }

    public function getCourse($courseId)
    {
        $courseRepo = new CourseRepo();

        $result = $courseRepo->findById($courseId);

        return $result;
    }

    public function getReview($id)
    {
        $result = $this->findOrFail($id);

        return $result;
    }

    public function updateReview($id)
    {
        $review = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new ReviewValidator();

        $data = [];

        if (isset($post['content'])) {
            $data['content'] = $validator->checkContent($post['content']);
        }

        if (isset($post['rating'])) {
            $data['rating'] = $validator->checkRating($post['rating']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        $review->update($data);

        return $review;
    }

    public function deleteReview($id)
    {
        $review = $this->findOrFail($id);

        $review->deleted = 1;

        $review->update();

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($review->course_id);

        $course->review_count -= 1;

        $course->update();
    }

    public function restoreReview($id)
    {
        $review = $this->findOrFail($id);

        $review->deleted = 0;

        $review->update();

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($review->course_id);

        $course->review_count += 1;

        $course->update();
    }

    protected function findOrFail($id)
    {
        $validator = new ReviewValidator();

        $result = $validator->checkReview($id);

        return $result;
    }

    protected function handleReviews($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new ReviewListBuilder();

            $pipeA = $pager->items->toArray();
            $pipeB = $builder->handleCourses($pipeA);
            $pipeC = $builder->handleUsers($pipeB);
            $pipeD = $builder->arrayToObject($pipeC);

            $pager->items = $pipeD;
        }

        return $pager;
    }

}
