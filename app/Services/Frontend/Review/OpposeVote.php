<?php

namespace App\Services\Frontend\Review;

use App\Models\ReviewVote as ReviewVoteModel;
use App\Repos\ReviewVote as ReviewVoteRepo;
use App\Services\Frontend\ReviewTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\UserDailyLimit as UserDailyLimitValidator;

class OpposeVote extends FrontendService
{

    use ReviewTrait, VoteTrait;

    public function handle($id)
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

}
