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
use App\Models\Review as ReviewModel;
use App\Repos\Course as CourseRepo;
use App\Repos\Review as ReviewRepo;
use App\Repos\User as UserRepo;
use App\Services\CourseStat as CourseStatService;
use App\Services\Logic\Review\ReviewInfo as ReviewInfoService;
use App\Traits\Client as ClientTrait;
use App\Validators\Review as ReviewValidator;
use Phalcon\Paginator\RepositoryInterface as PagerRepoInterface;

class Review extends Service
{

    use AccountSearchTrait;
    use ClientTrait;

    public function getPublishTypes(): array
    {
        return ReviewModel::publishTypes();
    }

    public function getXmCourses(): array
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

    public function getReviewInfo(int $id): array
    {
        $service = new ReviewInfoService();

        return $service->handle($id);
    }

    public function getReviews(): PagerRepoInterface
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

    public function createReview(): ReviewModel
    {
        $post = $this->request->getPost();

        $validator = new ReviewValidator();

        $course = $validator->checkCourse($post['course_id']);

        $review = new ReviewModel();

        $review->content = $validator->checkContent($post['content']);
        $review->rating1 = $validator->checkRating($post['rating1']);
        $review->rating2 = $validator->checkRating($post['rating2']);
        $review->rating3 = $validator->checkRating($post['rating3']);
        $review->client_type = $this->getClientType();
        $review->client_ip = $this->getClientIp();
        $review->owner_id = $this->getRandOwnerId();
        $review->published = ReviewModel::PUBLISH_APPROVED;
        $review->course_id = $course->id;
        $review->anonymous = 1;

        $review->create();

        $this->updateCourseReviews($review->course_id);
        $this->updateCourseRating($review->course_id);

        $this->eventsManager->fire('Review:afterCreate', $this, $review);

        return $review;
    }

    public function getReview(int $id): ReviewModel
    {
        return $this->findOrFail($id);
    }

    public function updateReview(int $id): ReviewModel
    {
        $review = $this->findOrFail($id);

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
        }

        $review->assign($data);

        $review->update();

        $this->updateCourseReviews($review->course_id);
        $this->updateCourseRating($review->course_id);

        $this->eventsManager->fire('Review:afterUpdate', $this, $review);

        return $review;
    }

    public function deleteReview(int $id): ReviewModel
    {
        $review = $this->findOrFail($id);

        $review->deleted = 1;

        $review->update();

        $this->updateCourseReviews($review->course_id);
        $this->updateCourseRating($review->course_id);

        $this->eventsManager->fire('Review:afterDelete', $this, $review);

        return $review;
    }

    public function restoreReview(int $id): ReviewModel
    {
        $review = $this->findOrFail($id);

        $review->deleted = 0;

        $review->update();

        $this->updateCourseReviews($review->course_id);
        $this->updateCourseRating($review->course_id);

        $this->eventsManager->fire('Review:afterRestore', $this, $review);

        return $review;
    }

    public function moderate(int $id): ReviewModel
    {
        $type = $this->request->getPost('type', ['trim', 'string']);

        $review = $this->findOrFail($id);

        if ($type == 'approve') {

            $review->published = ReviewModel::PUBLISH_APPROVED;
            $review->update();

            $this->eventsManager->fire('Review:afterApprove', $this, $review);

        } elseif ($type == 'reject') {

            $review->published = ReviewModel::PUBLISH_REJECTED;
            $review->update();

            $this->eventsManager->fire('Review:afterReject', $this, $review);
        }

        $this->updateCourseReviews($review->course_id);
        $this->updateCourseRating($review->course_id);

        return $review;
    }

    public function batchModerate(): void
    {
        $type = $this->request->getQuery('type', ['trim', 'string']);
        $ids = $this->request->getPost('ids', ['trim', 'int']);

        $reviewRepo = new ReviewRepo();

        $reviews = $reviewRepo->findByIds($ids);

        if ($reviews->count() == 0) return;

        foreach ($reviews as $review) {

            if ($type == 'approve') {
                $review->published = ReviewModel::PUBLISH_APPROVED;
                $review->update();
            } elseif ($type == 'reject') {
                $review->published = ReviewModel::PUBLISH_REJECTED;
                $review->update();
            }

            $this->updateCourseReviews($review->course_id);
            $this->updateCourseRating($review->course_id);
        }
    }

    public function batchDelete(): void
    {
        $ids = $this->request->getPost('ids', ['trim', 'int']);

        $reviewRepo = new ReviewRepo();

        $reviews = $reviewRepo->findByIds($ids);

        if ($reviews->count() == 0) return;

        foreach ($reviews as $review) {

            $review->deleted = 1;
            $review->update();

            $this->updateCourseReviews($review->course_id);
            $this->updateCourseRating($review->course_id);
        }
    }

    protected function findOrFail(int $id): ReviewModel
    {
        $validator = new ReviewValidator();

        return $validator->checkReview($id);
    }

    protected function getRandOwnerId(): int
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findByRand();

        return $user ? $user->id : 0;
    }

    protected function updateCourseReviews(int $courseId): void
    {
        $service = new CourseStatService();

        $service->updateReviewCount($courseId);
    }

    protected function updateCourseRating(int $courseId): void
    {
        $service = new CourseStatService();

        $service->updateRating($courseId);
    }

    protected function handleReviews(PagerRepoInterface $pager): PagerRepoInterface
    {
        if ($pager->getTotalItems() > 0) {

            $builder = new ReviewListBuilder();

            $pipeA = $pager->getItems()->toArray();
            $pipeB = $builder->handleCourses($pipeA);
            $pipeC = $builder->handleUsers($pipeB);
            $pipeD = $builder->objects($pipeC);

            $pager->setItems($pipeD);
        }

        return $pager;
    }

}
