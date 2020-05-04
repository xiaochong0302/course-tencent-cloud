<?php

namespace App\Services\Frontend\Review;

use App\Models\Review as ReviewModel;
use App\Models\User as UserModel;

trait VoteTrait
{

    protected function incrAgreeCount(ReviewModel $review)
    {
        $this->getEventsManager->fire('reviewCounter:incrAgreeCount', $this, $review);
    }

    protected function decrAgreeCount(ReviewModel $review)
    {
        $this->getEventsManager->fire('reviewCounter:decrAgreeCount', $this, $review);
    }

    protected function incrOpposeCount(ReviewModel $review)
    {
        $this->getEventsManager->fire('reviewCounter:incrOpposeCount', $this, $review);
    }

    protected function decrOpposeCount(ReviewModel $review)
    {
        $this->getEventsManager->fire('reviewCounter:decrOpposeCount', $this, $review);
    }

    protected function incrUserDailyReviewVoteCount(UserModel $user)
    {
        $this->getEventsManager->fire('userDailyCounter:incrReviewVoteCount', $this, $user);
    }

}
