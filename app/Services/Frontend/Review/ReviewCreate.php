<?php

namespace App\Services\Frontend\Review;

use App\Models\Course as CourseModel;
use App\Models\Review as ReviewModel;
use App\Models\User as UserModel;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\CourseUser as CourseUserValidator;
use App\Validators\Review as ReviewValidator;
use App\Validators\UserDailyLimit as UserDailyLimitValidator;

class ReviewCreate extends FrontendService
{

    use CourseTrait;

    public function handle()
    {
        $post = $this->request->getPost();

        $course = $this->checkCourseCache($post['course_id']);

        $user = $this->getLoginUser();

        $validator = new UserDailyLimitValidator();

        $validator->checkReviewLimit($user);

        $validator = new CourseUserValidator();

        $validator->checkCourseUser($course->id, $user->id);
        $validator->checkIfReviewed($course->id, $user->id);

        $validator = new ReviewValidator();

        $data = [];

        $data['content'] = $validator->checkContent($post['content']);
        $data['rating1'] = $validator->checkRating($post['rating1']);
        $data['rating2'] = $validator->checkRating($post['rating2']);
        $data['rating3'] = $validator->checkRating($post['rating3']);
        $data['course_id'] = $course->id;
        $data['user_id'] = $user->id;

        $review = new ReviewModel();

        $review->create($data);

        $this->incrCourseReviewCount($course);

        $this->incrUserDailyReviewCount($user);

        return $review;
    }

    protected function incrCourseReviewCount(CourseModel $course)
    {
        $this->eventsManager->fire('courseCounter:incrReviewCount', $this, $course);
    }

    protected function incrUserDailyReviewCount(UserModel $user)
    {
        $this->eventsManager->fire('userDailyCounter:incrReviewCount', $this, $user);
    }

}
