<?php

namespace App\Services\Frontend\Review;

use App\Models\Review as ReviewModel;
use App\Models\ReviewLike as ReviewLikeModel;
use App\Models\User as UserModel;
use App\Services\Frontend\ReviewTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\Review as ReviewValidator;
use App\Validators\UserDailyLimit as UserDailyLimitValidator;

class ReviewLike extends FrontendService
{

    use ReviewTrait;

    public function handle($id)
    {
        $review = $this->checkReview($id);

        $user = $this->getLoginUser();

        $validator = new UserDailyLimitValidator();

        $validator->checkReviewLikeLimit($user);

        $validator = new ReviewValidator();

        $reviewLike = $validator->checkIfLiked($review->id, $user->id);

        if (!$reviewLike) {

            $reviewLike = new ReviewLikeModel();

            $reviewLike->create([
                'review_id' => $review->id,
                'user_id' => $user->id,
            ]);

            $this->incrLikeCount($review);

        } else {

            if ($reviewLike->deleted == 0) {

                $reviewLike->update(['deleted' => 1]);

                $this->decrLikeCount($review);

            } else {

                $reviewLike->update(['deleted' => 0]);

                $this->incrLikeCount($review);
            }
        }

        $this->incrUserDailyReviewLikeCount($user);

        return $review;
    }

    protected function incrLikeCount(ReviewModel $review)
    {
        $review->like_count += 1;
        $review->update();
    }

    protected function decrLikeCount(ReviewModel $review)
    {
        if ($review->like_count > 0) {
            $review->like_count -= 1;
            $review->update();
        }
    }

    protected function incrUserDailyReviewLikeCount(UserModel $user)
    {
        $this->eventsManager->fire('userDailyCounter:incrReviewLikeCount', $this, $user);
    }

}
