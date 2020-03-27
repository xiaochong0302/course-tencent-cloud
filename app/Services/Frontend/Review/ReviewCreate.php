<?php

namespace App\Services\Frontend\Course;

use App\Models\Review as ReviewModel;
use App\Models\User as UserModel;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service;
use App\Validators\Review as ReviewValidator;
use App\Validators\UserDailyLimit as UserDailyLimitValidator;

class ReviewCreate extends Service
{

    use CourseTrait;

    public function createReview($id)
    {
        $post = $this->request->getPost();

        $course = $this->checkCourse($id);

        $user = $this->getLoginUser();

        $validator = new UserDailyLimitValidator();

        $validator->checkReviewLimit($user);

        $validator = new ReviewValidator();

        $validator->checkIfReviewed($course->id, $user->id);

        $content = $validator->checkContent($post['content']);
        $rating = $validator->checkRating($post['rating']);

        $review = new ReviewModel();

        $review->course_id = $course->id;
        $review->user_id = $user->id;
        $review->content = $content;
        $review->rating = $rating;

        $review->create();

        $course->review_count += 1;

        $course->update();

        $this->incrUserDailyReviewCount($user);
    }

    protected function incrUserDailyReviewCount(UserModel $user)
    {
        $this->eventsManager->fire('userDailyCounter:incrReviewCount', $this, $user);
    }

}
