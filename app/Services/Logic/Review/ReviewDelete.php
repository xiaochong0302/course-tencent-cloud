<?php

namespace App\Services\Logic\Review;

use App\Models\Course as CourseModel;
use App\Services\CourseStat as CourseStatService;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\ReviewTrait;
use App\Services\Logic\Service;
use App\Validators\Review as ReviewValidator;

class ReviewDelete extends Service
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

        $review->update(['deleted' => 1]);

        $this->decrCourseReviewCount($course);

        $this->updateCourseRating($course->id);
    }

    protected function decrCourseReviewCount(CourseModel $course)
    {
        if ($course->review_count > 0) {
            $course->review_count -= 1;
            $course->update();
        }
    }

    protected function updateCourseRating($courseId)
    {
        $service = new CourseStatService();

        $service->updateRating($courseId);
    }

}
