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

        $user = $this->getLoginUser();

        $validator = new ReviewValidator();

        $validator->checkOwner($user->id, $review->user_id);

        $content = $validator->checkContent($post['content']);
        $rating = $validator->checkRating($post['rating']);

        $review->content = $content;
        $review->rating = $rating;
        $review->update();
    }

}
