<?php

namespace App\Services\Frontend\Comment;

use App\Models\Comment as CommentModel;
use App\Models\User as UserModel;
use Phalcon\Di as Di;
use Phalcon\Events\Manager as EventsManager;

trait VoteTrait
{

    protected function incrAgreeCount(CommentModel $comment)
    {
        $this->getPhEventsManager()->fire('commentCounter:incrAgreeCount', $this, $comment);
    }

    protected function decrAgreeCount(CommentModel $comment)
    {
        $this->getPhEventsManager()->fire('commentCounter:decrAgreeCount', $this, $comment);
    }

    protected function incrOpposeCount(CommentModel $comment)
    {
        $this->getPhEventsManager()->fire('commentCounter:incrOpposeCount', $this, $comment);
    }

    protected function decrOpposeCount(CommentModel $comment)
    {
        $this->getPhEventsManager()->fire('commentCounter:decrOpposeCount', $this, $comment);
    }

    protected function incrUserDailyCommentVoteCount(UserModel $user)
    {
        $this->getPhEventsManager()->fire('userDailyCounter:incrCommentVoteCount', $this, $user);
    }

    /**
     * @return EventsManager
     */
    protected function getPhEventsManager()
    {
        return Di::getDefault()->get('eventsManager');
    }

}
