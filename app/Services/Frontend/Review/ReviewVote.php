<?php

namespace App\Services\Frontend\Review;

use App\Models\Review as ReviewModel;
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

            $this->incrAgreeCount($review);

        } else {

            if ($reviewVote->type == ReviewVoteModel::TYPE_AGREE) {

                $reviewVote->type = ReviewVoteModel::TYPE_NONE;

                $this->decrAgreeCount($review);

            } elseif ($reviewVote->type == ReviewVoteModel::TYPE_OPPOSE) {

                $reviewVote->type = ReviewVoteModel::TYPE_AGREE;

                $this->incrAgreeCount($review);

                $this->decrOpposeCount($review);

            } elseif ($reviewVote->type == ReviewVoteModel::TYPE_NONE) {

                $reviewVote->type = ReviewVoteModel::TYPE_AGREE;

                $this->incrAgreeCount($review);
            }

            $reviewVote->update();
        }

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

            $this->incrOpposeCount($review);

        } else {

            if ($reviewVote->type == ReviewVoteModel::TYPE_AGREE) {

                $reviewVote->type = ReviewVoteModel::TYPE_OPPOSE;

                $this->decrAgreeCount($review);

                $this->incrOpposeCount($review);

            } elseif ($reviewVote->type == ReviewVoteModel::TYPE_OPPOSE) {

                $reviewVote->type = ReviewVoteModel::TYPE_NONE;

                $this->decrOpposeCount($review);

            } elseif ($reviewVote->type == ReviewVoteModel::TYPE_NONE) {

                $reviewVote->type = ReviewVoteModel::TYPE_OPPOSE;

                $this->incrOpposeCount($review);
            }

            $reviewVote->update();
        }

        $this->incrUserDailyReviewVoteCount($user);

        return $review;
    }

    protected function incrAgreeCount(ReviewModel $review)
    {
        $this->eventsManager->fire('reviewCounter:incrAgreeCount', $this, $review);
    }

    protected function decrAgreeCount(ReviewModel $review)
    {
        $this->eventsManager->fire('reviewCounter:decrAgreeCount', $this, $review);
    }

    protected function incrOpposeCount(ReviewModel $review)
    {
        $this->eventsManager->fire('reviewCounter:incrOpposeCount', $this, $review);
    }

    protected function decrOpposeCount(ReviewModel $review)
    {
        $this->eventsManager->fire('reviewCounter:decrOpposeCount', $this, $review);
    }

    protected function incrUserDailyReviewVoteCount(UserModel $user)
    {
        $this->eventsManager->fire('userDailyCounter:incrReviewVoteCount', $this, $user);
    }

}
