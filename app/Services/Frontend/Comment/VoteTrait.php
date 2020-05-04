<?php

namespace App\Services\Frontend\Comment;

use App\Models\Comment as CommentModel;
use App\Models\User as UserModel;

trait VoteTrait
{

    protected function incrAgreeCount(CommentModel $comment)
    {
        $this->getEventsManager->fire('commentCounter:incrAgreeCount', $this, $comment);
    }

    protected function decrAgreeCount(CommentModel $comment)
    {
        $this->getEventsManager->fire('commentCounter:decrAgreeCount', $this, $comment);
    }

    protected function incrOpposeCount(CommentModel $comment)
    {
        $this->getEventsManager->fire('commentCounter:incrOpposeCount', $this, $comment);
    }

    protected function decrOpposeCount(CommentModel $comment)
    {
        $this->getEventsManager->fire('commentCounter:decrOpposeCount', $this, $comment);
    }

    protected function incrUserDailyCommentVoteCount(UserModel $user)
    {
        $this->getEventsManager->fire('userDailyCounter:incrCommentVoteCount', $this, $user);
    }

}
