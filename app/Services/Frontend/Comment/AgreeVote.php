<?php

namespace App\Services\Frontend\Comment;

use App\Models\CommentVote as CommentVoteModel;
use App\Repos\CommentVote as CommentVoteRepo;
use App\Services\Frontend\CommentTrait;
use App\Services\Frontend\Service;
use App\Validators\UserDailyLimit as UserDailyLimitValidator;

class AgreeVote extends Service
{

    use CommentTrait, VoteTrait;

    public function handle($id)
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

}
