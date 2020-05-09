<?php

namespace App\Services\Frontend\Review;

use App\Models\Course as CourseModel;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\ReviewTrait;
use App\Services\Frontend\Service;
use App\Validators\Review as ReviewValidator;

class ReviewDelete extends Service
{

    use CourseTrait, ReviewTrait;

    public function handle($id)
    {
        $review = $this->checkReview($id);

        $course = $this->checkCourseCache($review->course_id);

        $user = $this->getLoginUser();

        $validator = new ReviewValidator();

        $validator->checkOwner($user->id, $review->user_id);

        $review->delete();

        $this->decrCourseReviewCount($course);
    }

    protected function decrCourseReviewCount(CourseModel $course)
    {
        $this->eventsManager->fire('courseCounter:decrReviewCount', $this, $course);
    }

}
