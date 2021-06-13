<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Builders\ReviewList as ReviewListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Course as CourseModel;
use App\Repos\Course as CourseRepo;
use App\Repos\Review as ReviewRepo;
use App\Services\CourseStat as CourseStatService;
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

        return $courseRepo->findById($courseId);
    }

    public function getReview($id)
    {
        return $this->findOrFail($id);
    }

    public function updateReview($id)
    {
        $review = $this->findOrFail($id);

        $course = $this->findCourse($review->course_id);

        $post = $this->request->getPost();

        $validator = new ReviewValidator();

        $data = [];

        if (isset($post['content'])) {
            $data['content'] = $validator->checkContent($post['content']);
        }

        if (isset($post['rating1'])) {
            $data['rating1'] = $validator->checkRating($post['rating1']);
        }

        if (isset($post['rating2'])) {
            $data['rating2'] = $validator->checkRating($post['rating2']);
        }

        if (isset($post['rating3'])) {
            $data['rating3'] = $validator->checkRating($post['rating3']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
            $this->recountCourseReviews($course);
        }

        $review->update($data);

        $this->updateCourseRating($course);

        return $review;
    }

    public function deleteReview($id)
    {
        $review = $this->findOrFail($id);

        $review->deleted = 1;

        $review->update();

        $course = $this->findCourse($review->course_id);

        $this->recountCourseReviews($course);
    }

    public function restoreReview($id)
    {
        $review = $this->findOrFail($id);

        $review->deleted = 0;

        $review->update();

        $course = $this->findCourse($review->course_id);

        $this->recountCourseReviews($course);
    }

    protected function findOrFail($id)
    {
        $validator = new ReviewValidator();

        return $validator->checkReview($id);
    }

    protected function findCourse($id)
    {
        $courseRepo = new CourseRepo();

        return $courseRepo->findById($id);
    }

    protected function recountCourseReviews(CourseModel $course)
    {
        $courseRepo = new CourseRepo();

        $reviewCount = $courseRepo->countReviews($course->id);

        $course->review_count = $reviewCount;

        $course->update();
    }

    protected function updateCourseRating(CourseModel $course)
    {
        $service = new CourseStatService();

        $service->updateRating($course->id);
    }

    protected function handleReviews($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new ReviewListBuilder();

            $pipeA = $pager->items->toArray();
            $pipeB = $builder->handleCourses($pipeA);
            $pipeC = $builder->handleUsers($pipeB);
            $pipeD = $builder->objects($pipeC);

            $pager->items = $pipeD;
        }

        return $pager;
    }

}
