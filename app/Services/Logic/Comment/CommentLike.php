<?php

namespace App\Services\Logic\Comment;

use App\Models\Comment as CommentModel;
use App\Models\CommentLike as CommentLikeModel;
use App\Models\User as UserModel;
use App\Repos\CommentLike as CommentLikeRepo;
use App\Services\Logic\CommentTrait;
use App\Services\Logic\Notice\System\CommentLiked as CommentLikedNotice;
use App\Services\Logic\Service as LogicService;
use App\Validators\UserLimit as UserLimitValidator;

class CommentLike extends LogicService
{

    use CommentTrait;

    public function handle($id)
    {
        $comment = $this->checkComment($id);

        $user = $this->getLoginUser();

        $validator = new UserLimitValidator();

        $validator->checkDailyCommentLikeLimit($user);

        $likeRepo = new CommentLikeRepo();

        $commentLike = $likeRepo->findCommentLike($comment->id, $user->id);

        if (!$commentLike) {

            $action = 'do';

            $commentLike = new CommentLikeModel();

            $commentLike->comment_id = $comment->id;
            $commentLike->user_id = $user->id;

            $commentLike->create();

            $this->incrCommentLikeCount($comment);

            $this->incrUserDailyCommentLikeCount($user);

            $this->handleLikeNotice($comment, $user);

            $this->eventsManager->fire('Comment:afterLike', $this, $comment);

        } else {

            $action = 'undo';

            $commentLike->delete();

            $this->decrCommentLikeCount($comment);

            $this->eventsManager->fire('Comment:afterUndoLike', $this, $comment);
        }

        return [
            'action' => $action,
            'count' => $comment->like_count,
        ];
    }

    protected function incrCommentLikeCount(CommentModel $comment)
    {
        $comment->like_count += 1;

        $comment->update();
    }


    protected function decrCommentLikeCount(CommentModel $comment)
    {
        if ($comment->like_count > 0) {
            $comment->like_count -= 1;
            $comment->update();
        }
    }

    protected function incrUserDailyCommentLikeCount(UserModel $user)
    {
        $this->eventsManager->fire('UserDailyCounter:incrCommentLikeCount', $this, $user);
    }

    protected function handleLikeNotice(CommentModel $comment, UserModel $sender)
    {
        $notice = new CommentLikedNotice();

        $notice->handle($comment, $sender);
    }

}
