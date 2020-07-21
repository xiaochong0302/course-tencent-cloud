<?php

namespace App\Services\Frontend\Review;

use App\Models\Course as CourseModel;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\ReviewTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\Review as ReviewValidator;

class ReviewDelete extends FrontendService
{

    use CourseTrait;
    use ReviewTrait;

    public function handle($id)
    {
        $review = $this->checkReview($id);

        $course = $this->checkCourse($review->course_id);

        $user = $this->getLoginUser();

        $validator = new ReviewValidator();

        $validator->checkOwner($user->id, $review->user_id);

        $review->delete();

        $this->decrCourseReviewCount($course);

        $this->updateCourseRating($course);
    }

    protected function decrCourseReviewCount(CourseModel $course)
    {
        $course->review_count -= 1;

        $course->update();
    }

}
