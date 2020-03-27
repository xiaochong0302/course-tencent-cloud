<?php

namespace App\Services\Frontend\Comment;

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

            $comment->agree_count += 1;

        } else {

            if ($commentVote->type == CommentVoteModel::TYPE_AGREE) {

                $commentVote->type = CommentVoteModel::TYPE_NONE;

                $comment->agree_count -= 1;

            } elseif ($commentVote->type == CommentVoteModel::TYPE_OPPOSE) {

                $commentVote->type = CommentVoteModel::TYPE_AGREE;

                $comment->agree_count += 1;
                $comment->oppose_count -= 1;

            } elseif ($commentVote->type == CommentVoteModel::TYPE_NONE) {

                $commentVote->type = CommentVoteModel::TYPE_AGREE;

                $comment->agree_count += 1;
            }

            $commentVote->update();
        }

        $comment->update();

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

            $comment->oppose_count += 1;

        } else {

            if ($commentVote->type == CommentVoteModel::TYPE_AGREE) {

                $commentVote->type = CommentVoteModel::TYPE_OPPOSE;

                $comment->agree_count -= 1;
                $comment->oppose_count += 1;

            } elseif ($commentVote->type == CommentVoteModel::TYPE_OPPOSE) {

                $commentVote->type = CommentVoteModel::TYPE_NONE;

                $comment->oppose_count -= 1;

            } elseif ($commentVote->type == CommentVoteModel::TYPE_NONE) {

                $commentVote->type = CommentVoteModel::TYPE_OPPOSE;

                $comment->oppose_count += 1;
            }

            $commentVote->update();
        }

        $comment->update();

        $this->incrUserDailyCommentVoteCount($user);

        return $comment;
    }

    protected function incrUserDailyCommentVoteCount(UserModel $user)
    {
        $this->eventsManager->fire('userDailyCounter:incrCommentVoteCount', $this, $user);
    }

}
