<?php

namespace App\Services\Logic\Review;

use App\Models\Course as CourseModel;
use App\Services\CourseStat as CourseStatService;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\ReviewTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Review as ReviewValidator;

class ReviewDelete extends LogicService
{

    use CourseTrait;
    use ReviewTrait;

    public function handle($id)
    {
        $review = $this->checkReview($id);

        $course = $this->checkCourse($review->course_id);

        $user = $this->getLoginUser();

        $validator = new ReviewValidator();

        $validator->checkOwner($user->id, $review->owner_id);

        $review->deleted = 1;

        $review->update();

        $this->decrCourseReviewCount($course);

        $this->updateCourseRating($course);

        $this->eventsManager->fire('Review:afterDelete', $this, $review);
    }

    protected function decrCourseReviewCount(CourseModel $course)
    {
        if ($course->review_count > 0) {
            $course->review_count -= 1;
            $course->update();
        }
    }

    protected function updateCourseRating(CourseModel $course)
    {
        $service = new CourseStatService();

        $service->updateRating($course->id);
    }

}
