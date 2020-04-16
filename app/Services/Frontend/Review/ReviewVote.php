<?php

namespace App\Services\Frontend\Review;

use App\Models\ReviewVote as ReviewVoteModel;
use App\Models\User as UserModel;
use App\Repos\ReviewVote as ReviewVoteRepo;
use App\Services\Frontend\ReviewTrait;
use App\Services\Frontend\Service;
use App\Validators\UserDailyLimit as UserDailyLimitValidator;

class ReviewVote extends Service
{

    use ReviewTrait;

    public function agree($id)
    {
        $review = $this->checkReview($id);

        $user = $this->getLoginUser();

        $validator = new UserDailyLimitValidator();

        $validator->checkReviewVoteLimit($user);

        $reviewVoteRepo = new ReviewVoteRepo();

        $reviewVote = $reviewVoteRepo->findReviewVote($review->id, $user->id);

        if (!$reviewVote) {

            $reviewVote = new ReviewVoteModel();

            $reviewVote->review_id = $review->id;
            $reviewVote->user_id = $user->id;
            $reviewVote->type = ReviewVoteModel::TYPE_AGREE;

            $reviewVote->create();

            $review->agree_count += 1;

        } else {

            if ($reviewVote->type == ReviewVoteModel::TYPE_AGREE) {

                $reviewVote->type = ReviewVoteModel::TYPE_NONE;

                $review->agree_count -= 1;

            } elseif ($reviewVote->type == ReviewVoteModel::TYPE_OPPOSE) {

                $reviewVote->type = ReviewVoteModel::TYPE_AGREE;

                $review->agree_count += 1;
                $review->oppose_count -= 1;

            } elseif ($reviewVote->type == ReviewVoteModel::TYPE_NONE) {

                $reviewVote->type = ReviewVoteModel::TYPE_AGREE;

                $review->agree_count += 1;
            }

            $reviewVote->update();
        }

        $review->update();

        $this->incrUserDailyReviewVoteCount($user);

        return $review;
    }

    public function oppose($id)
    {
        $review = $this->checkReview($id);

        $user = $this->getLoginUser();

        $validator = new UserDailyLimitValidator();

        $validator->checkReviewVoteLimit($user);

        $reviewVoteRepo = new ReviewVoteRepo();

        $reviewVote = $reviewVoteRepo->findReviewVote($review->id, $user->id);

        if (!$reviewVote) {

            $reviewVote = new ReviewVoteModel();

            $reviewVote->review_id = $review->id;
            $reviewVote->user_id = $user->id;
            $reviewVote->type = ReviewVoteModel::TYPE_OPPOSE;

            $reviewVote->create();

            $review->oppose_count += 1;

        } else {

            if ($reviewVote->type == ReviewVoteModel::TYPE_AGREE) {

                $reviewVote->type = ReviewVoteModel::TYPE_OPPOSE;

                $review->agree_count -= 1;
                $review->oppose_count += 1;

            } elseif ($reviewVote->type == ReviewVoteModel::TYPE_OPPOSE) {

                $reviewVote->type = ReviewVoteModel::TYPE_NONE;

                $review->oppose_count -= 1;

            } elseif ($reviewVote->type == ReviewVoteModel::TYPE_NONE) {

                $reviewVote->type = ReviewVoteModel::TYPE_OPPOSE;

                $review->oppose_count += 1;
            }

            $reviewVote->update();
        }

        $review->update();

        $this->incrUserDailyReviewVoteCount($user);

        return $review;
    }

    protected function incrUserDailyReviewVoteCount(UserModel $user)
    {
        $this->eventsManager->fire('userDailyCounter:incrReviewVoteCount', $this, $user);
    }

}
