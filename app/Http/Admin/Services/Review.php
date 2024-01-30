<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Builders\ReviewList as ReviewListBuilder;
use App\Http\Admin\Services\Traits\AccountSearchTrait;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Course as CourseModel;
use App\Models\Reason as ReasonModel;
use App\Models\Review as ReviewModel;
use App\Models\User as UserModel;
use App\Repos\Course as CourseRepo;
use App\Repos\Review as ReviewRepo;
use App\Services\CourseStat as CourseStatService;
use App\Services\Logic\Review\ReviewInfo as ReviewInfoService;
use App\Validators\Review as ReviewValidator;

class Review extends Service
{

    use AccountSearchTrait;

    public function getPublishTypes()
    {
        return ReviewModel::publishTypes();
    }

    public function getReasons()
    {
        return ReasonModel::reviewRejectOptions();
    }

    public function getXmCourses()
    {
        $courseRepo = new CourseRepo();

        $items = $courseRepo->findAll([
            'published' => 1,
            'deleted' => 0,
        ]);

        if ($items->count() == 0) return [];

        $result = [];

        foreach ($items as $item) {
            $result[] = [
                'name' => sprintf('%s - %s（¥%0.2f）', $item->id, $item->title, $item->market_price),
                'value' => $item->id,
            ];
        }

        return $result;
    }

    public function getReviews()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params = $this->handleAccountSearchParams($params);

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

    public function getReviewInfo($id)
    {
        $service = new ReviewInfoService();

        return $service->handle($id);
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

        if (isset($post['anonymous'])) {
            $data['anonymous'] = $validator->checkAnonymous($post['anonymous']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
            $this->recountCourseReviews($course);
        }

        $review->update($data);

        $this->updateCourseRating($course);
        $this->recountCourseReviews($course);

        $this->eventsManager->fire('Review:afterUpdate', $this, $review);

        return $review;
    }

    public function deleteReview($id)
    {
        $review = $this->findOrFail($id);

        $review->deleted = 1;

        $review->update();

        $course = $this->findCourse($review->course_id);

        $this->recountCourseReviews($course);

        $this->updateCourseRating($course);

        $sender = $this->getLoginUser();

        $this->handleReviewDeletedNotice($review, $sender);

        $this->eventsManager->fire('Review:afterReview', $this, $review);
    }

    public function restoreReview($id)
    {
        $review = $this->findOrFail($id);

        $review->deleted = 0;

        $review->update();

        $course = $this->findCourse($review->course_id);

        $this->recountCourseReviews($course);

        $this->eventsManager->fire('Review:afterRestore', $this, $review);
    }

    public function moderate($id)
    {
        $type = $this->request->getPost('type', ['trim', 'string']);
        $reason = $this->request->getPost('reason', ['trim', 'string']);

        $review = $this->findOrFail($id);

        $sender = $this->getLoginUser();

        if ($type == 'approve') {

            $review->published = ReviewModel::PUBLISH_APPROVED;
            $review->update();

            $this->handleReviewApprovedNotice($review, $sender);

            $this->eventsManager->fire('Review:afterApprove', $this, $review);

        } elseif ($type == 'reject') {

            $review->published = ReviewModel::PUBLISH_REJECTED;
            $review->update();

            $this->handleReviewRejectedNotice($review, $sender, $reason);

            $this->eventsManager->fire('Review:afterReject', $this, $review);
        }

        $course = $this->findCourse($review->course_id);

        $this->recountCourseReviews($course);
        $this->updateCourseRating($course);

        return $review;
    }

    public function batchModerate()
    {
        $type = $this->request->getQuery('type', ['trim', 'string']);
        $ids = $this->request->getPost('ids', ['trim', 'int']);

        $reviewRepo = new ReviewRepo();

        $reviews = $reviewRepo->findByIds($ids);

        if ($reviews->count() == 0) return;

        $sender = $this->getLoginUser();

        foreach ($reviews as $review) {

            if ($type == 'approve') {

                $review->published = ReviewModel::PUBLISH_APPROVED;
                $review->update();

                $this->handleReviewApprovedNotice($review, $sender);

            } elseif ($type == 'reject') {

                $review->published = ReviewModel::PUBLISH_REJECTED;
                $review->update();

                $this->handleReviewRejectedNotice($review, $sender);
            }

            $course = $this->findCourse($review->course_id);

            $this->recountCourseReviews($course);
            $this->updateCourseRating($course);
        }
    }

    public function batchDelete()
    {
        $ids = $this->request->getPost('ids', ['trim', 'int']);

        $reviewRepo = new ReviewRepo();

        $reviews = $reviewRepo->findByIds($ids);

        if ($reviews->count() == 0) return;

        $sender = $this->getLoginUser();

        foreach ($reviews as $review) {

            $review->deleted = 1;
            $review->update();

            $this->handleReviewDeletedNotice($review, $sender);

            $course = $this->findCourse($review->course_id);

            $this->recountCourseReviews($course);
            $this->updateCourseRating($course);
        }
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

    protected function handleReviewApprovedNotice(ReviewModel $review, UserModel $sender)
    {

    }

    protected function handleReviewRejectedNotice(ReviewModel $review, UserModel $sender, $reason = '')
    {

    }

    protected function handleReviewDeletedNotice(ReviewModel $review, UserModel $sender, $reason = '')
    {

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
