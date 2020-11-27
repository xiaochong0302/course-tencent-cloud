<?php

namespace App\Services\Logic\Review;

use App\Models\Course as CourseModel;
use App\Services\CourseStat as CourseStatService;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\ReviewTrait;
use App\Services\Logic\Service;
use App\Validators\Review as ReviewValidator;

class ReviewUpdate extends Service
{

    use CourseTrait;
    use ReviewTrait;

    public function handle($id)
    {
        $post = $this->request->getPost();

        $review = $this->checkReview($id);

        $course = $this->checkCourse($review->course_id);

        $user = $this->getLoginUser();

        $validator = new ReviewValidator();

        $validator->checkOwner($user->id, $review->owner_id);

        $validator->checkIfAllowEdit($review);

        $data = [];

        $data['content'] = $validator->checkContent($post['content']);
        $data['rating1'] = $validator->checkRating($post['rating1']);
        $data['rating2'] = $validator->checkRating($post['rating2']);
        $data['rating3'] = $validator->checkRating($post['rating3']);

        $review->update($data);

        $this->updateCourseRating($course);
    }

    protected function updateCourseRating(CourseModel $course)
    {
        $service = new CourseStatService();

        $service->updateRating($course->id);
    }

}
