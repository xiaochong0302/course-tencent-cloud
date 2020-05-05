<?php

namespace App\Services\Frontend\Review;

use App\Models\Review as ReviewModel;
use App\Models\User as UserModel;
use Phalcon\Di as Di;
use Phalcon\Events\Manager as EventsManager;

trait VoteTrait
{

    protected function incrAgreeCount(ReviewModel $review)
    {
        $this->getPhEventsManager()->fire('reviewCounter:incrAgreeCount', $this, $review);
    }

    protected function decrAgreeCount(ReviewModel $review)
    {
        $this->getPhEventsManager()->fire('reviewCounter:decrAgreeCount', $this, $review);
    }

    protected function incrOpposeCount(ReviewModel $review)
    {
        $this->getPhEventsManager()->fire('reviewCounter:incrOpposeCount', $this, $review);
    }

    protected function decrOpposeCount(ReviewModel $review)
    {
        $this->getPhEventsManager()->fire('reviewCounter:decrOpposeCount', $this, $review);
    }

    protected function incrUserDailyReviewVoteCount(UserModel $user)
    {
        $this->getPhEventsManager()->fire('userDailyCounter:incrReviewVoteCount', $this, $user);
    }

    /**
     * @return EventsManager
     */
    protected function getPhEventsManager()
    {
        return Di::getDefault()->get('eventsManager');
    }

}
