<?php

namespace App\Services\Logic\Review;

use App\Models\Review as ReviewModel;
use App\Models\ReviewLike as ReviewLikeModel;
use App\Models\User as UserModel;
use App\Repos\ReviewLike as ReviewLikeRepo;
use App\Services\Logic\ReviewTrait;
use App\Services\Logic\Service;
use App\Validators\UserLimit as UserLimitValidator;

class ReviewLike extends Service
{

    use ReviewTrait;

    public function handle($id)
    {
        $review = $this->checkReview($id);

        $user = $this->getLoginUser();

        $validator = new UserLimitValidator();

        $validator->checkDailyReviewLikeLimit($user);

        $likeRepo = new ReviewLikeRepo();

        $reviewLike = $likeRepo->findReviewLike($review->id, $user->id);

        if (!$reviewLike) {

            $reviewLike = new ReviewLikeModel();

            $reviewLike->review_id = $review->id;
            $reviewLike->user_id = $user->id;

            $reviewLike->create();

            $this->incrReviewLikeCount($review);

        } else {

            $reviewLike->delete();

            $this->decrReviewLikeCount($review);
        }

        $this->incrUserDailyReviewLikeCount($user);

        return $review->like_count;
    }

    protected function incrReviewLikeCount(ReviewModel $review)
    {
        $review->like_count += 1;

        $review->update();
    }

    protected function decrReviewLikeCount(ReviewModel $review)
    {
        if ($review->like_count > 0) {
            $review->like_count -= 1;
            $review->update();
        }
    }

    protected function incrUserDailyReviewLikeCount(UserModel $user)
    {
        $this->eventsManager->fire('UserDailyCounter:incrReviewLikeCount', $this, $user);
    }

}
