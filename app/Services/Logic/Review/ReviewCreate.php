<?php

namespace App\Services\Logic\Review;

use App\Models\Course as CourseModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\Review as ReviewModel;
use App\Repos\Course as CourseRepo;
use App\Services\CourseStat as CourseStatService;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Point\History\CourseReview as CourseReviewPointHistory;
use App\Services\Logic\ReviewTrait;
use App\Services\Logic\Service as LogicService;
use App\Traits\Client as ClientTrait;
use App\Validators\CourseUser as CourseUserValidator;
use App\Validators\Review as ReviewValidator;

class ReviewCreate extends LogicService
{

    use ClientTrait;
    use CourseTrait;
    use ReviewTrait;

    public function handle()
    {
        $post = $this->request->getPost();

        $course = $this->checkCourse($post['course_id']);

        $user = $this->getLoginUser();

        $validator = new CourseUserValidator();

        $courseUser = $validator->checkCourseUser($course->id, $user->id);

        $validator->checkIfReviewed($course->id, $user->id);

        $validator = new ReviewValidator();

        $data = [
            'client_type' => $this->getClientType(),
            'client_ip' => $this->getClientIp(),
            'course_id' => $course->id,
            'owner_id' => $user->id,
        ];

        $data['content'] = $validator->checkContent($post['content']);
        $data['rating1'] = $validator->checkRating($post['rating1']);
        $data['rating2'] = $validator->checkRating($post['rating2']);
        $data['rating3'] = $validator->checkRating($post['rating3']);
        $data['published'] = 1;

        $review = new ReviewModel();

        $review->create($data);

        $this->updateCourseUserReview($courseUser);
        $this->recountCourseReviews($course);
        $this->updateCourseRating($course);
        $this->handleReviewPoint($review);

        $this->eventsManager->fire('Review:afterCreate', $this, $review);

        return $review;
    }

    protected function updateCourseUserReview(CourseUserModel $courseUser)
    {
        $courseUser->reviewed = 1;

        $courseUser->update();
    }

    protected function updateCourseRating(CourseModel $course)
    {
        $service = new CourseStatService();

        $service->updateRating($course->id);
    }

    protected function recountCourseReviews(CourseModel $course)
    {
        $courseRepo = new CourseRepo();

        $reviewCount = $courseRepo->countReviews($course->id);

        $course->review_count = $reviewCount;

        $course->update();
    }

    protected function handleReviewPoint(ReviewModel $review)
    {
        $service = new CourseReviewPointHistory();

        $service->handle($review);
    }

}
