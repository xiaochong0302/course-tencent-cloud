<?php

namespace App\Services\Frontend\Review;

use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\ReviewTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\Review as ReviewValidator;

class ReviewUpdate extends FrontendService
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

}
