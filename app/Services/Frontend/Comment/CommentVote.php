<?php

namespace App\Services\Frontend\Comment;

use App\Models\Comment as CommentModel;
use App\Models\CommentVote as CommentVoteModel;
use App\Models\User as UserModel;
use App\Repos\CommentVote as CommentVoteRepo;
use App\Services\Frontend\CommentTrait;
use App\Services\Frontend\Service;
use App\Validators\UserDailyLimit as UserDailyLimitValidator;

class CommentVote extends Service
{

    use CommentTrait;

    public function agree($id)
    {
        $comment = $this->checkComment($id);

        $user = $this->getLoginUser();

        $validator = new UserDailyLimitValidator();

        $validator->checkCommentVoteLimit($user);

        $commentVoteRepo = new CommentVoteRepo();

        $commentVote = $commentVoteRepo->findCommentVote($comment->id, $user->id);

        if (!$commentVote) {

            $commentVote = new CommentVoteModel();

            $commentVote->comment_id = $comment->id;
            $commentVote->user_id = $user->id;
            $commentVote->type = CommentVoteModel::TYPE_AGREE;

            $commentVote->create();

            $this->incrAgreeCount($comment);

        } else {

            if ($commentVote->type == CommentVoteModel::TYPE_AGREE) {

                $commentVote->type = CommentVoteModel::TYPE_NONE;

                $this->decrAgreeCount($comment);

            } elseif ($commentVote->type == CommentVoteModel::TYPE_OPPOSE) {

                $commentVote->type = CommentVoteModel::TYPE_AGREE;

                $this->incrAgreeCount($comment);

                $this->decrOpposeCount($comment);

            } elseif ($commentVote->type == CommentVoteModel::TYPE_NONE) {

                $commentVote->type = CommentVoteModel::TYPE_AGREE;

                $this->incrAgreeCount($comment);
            }

            $commentVote->update();
        }

        $this->incrUserDailyCommentVoteCount($user);

        return $comment;
    }

    public function oppose($id)
    {
        $comment = $this->checkComment($id);

        $user = $this->getLoginUser();

        $validator = new UserDailyLimitValidator();

        $validator->checkCommentVoteLimit($user);

        $commentVoteRepo = new CommentVoteRepo();

        $commentVote = $commentVoteRepo->findCommentVote($comment->id, $user->id);

        if (!$commentVote) {

            $commentVote = new CommentVoteModel();

            $commentVote->comment_id = $comment->id;
            $commentVote->user_id = $user->id;
            $commentVote->type = CommentVoteModel::TYPE_OPPOSE;

            $commentVote->create();

            $this->incrOpposeCount($comment);

        } else {

            if ($commentVote->type == CommentVoteModel::TYPE_AGREE) {

                $commentVote->type = CommentVoteModel::TYPE_OPPOSE;

                $this->decrAgreeCount($comment);

                $this->incrOpposeCount($comment);

            } elseif ($commentVote->type == CommentVoteModel::TYPE_OPPOSE) {

                $commentVote->type = CommentVoteModel::TYPE_NONE;

                $this->decrOpposeCount($comment);

            } elseif ($commentVote->type == CommentVoteModel::TYPE_NONE) {

                $commentVote->type = CommentVoteModel::TYPE_OPPOSE;

                $this->incrOpposeCount($comment);
            }

            $commentVote->update();
        }

        $this->incrUserDailyCommentVoteCount($user);

        return $comment;
    }

    protected function incrAgreeCount(CommentModel $comment)
    {
        $this->eventsManager->fire('commentCounter:incrAgreeCount', $this, $comment);
    }

    protected function decrAgreeCount(CommentModel $comment)
    {
        $this->eventsManager->fire('commentCounter:decrAgreeCount', $this, $comment);
    }

    protected function incrOpposeCount(CommentModel $comment)
    {
        $this->eventsManager->fire('commentCounter:incrOpposeCount', $this, $comment);
    }

    protected function decrOpposeCount(CommentModel $comment)
    {
        $this->eventsManager->fire('commentCounter:decrOpposeCount', $this, $comment);
    }

    protected function incrUserDailyCommentVoteCount(UserModel $user)
    {
        $this->eventsManager->fire('userDailyCounter:incrCommentVoteCount', $this, $user);
    }

}
