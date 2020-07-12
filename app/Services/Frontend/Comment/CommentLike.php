<?php

namespace App\Services\Frontend\Comment;

use App\Models\Comment as CommentModel;
use App\Models\CommentLike as CommentLikeModel;
use App\Models\User as UserModel;
use App\Repos\CommentLike as CommentLikeRepo;
use App\Services\Frontend\CommentTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\UserDailyLimit as UserDailyLimitValidator;
use Phalcon\Di as Di;
use Phalcon\Events\Manager as EventsManager;

class CommentLike extends FrontendService
{

    use CommentTrait;

    public function handle($id)
    {
        $comment = $this->checkComment($id);

        $user = $this->getLoginUser();

        $validator = new UserDailyLimitValidator();

        $validator->checkCommentLikeLimit($user);

        $commentLikeRepo = new CommentLikeRepo();

        $commentLike = $commentLikeRepo->findCommentLike($comment->id, $user->id);

        if (!$commentLike) {

            $commentLike = new CommentLikeModel();

            $commentLike->create([
                'comment_id' => $comment->id,
                'user_id' => $user->id,
            ]);

            $this->incrLikeCount($comment);

        } else {

            if ($commentLike->deleted == 0) {

                $commentLike->update(['deleted' => 1]);

                $this->decrLikeCount($comment);

            } else {

                $commentLike->update(['deleted' => 0]);

                $this->incrLikeCount($comment);
            }
        }

        $this->incrUserDailyCommentLikeCount($user);

        return $comment;
    }

    protected function incrLikeCount(CommentModel $comment)
    {
        $this->getPhEventsManager()->fire('commentCounter:incrLikeCount', $this, $comment);
    }

    protected function decrLikeCount(CommentModel $comment)
    {
        $this->getPhEventsManager()->fire('commentCounter:decrLikeCount', $this, $comment);
    }

    protected function incrUserDailyCommentLikeCount(UserModel $user)
    {
        $this->getPhEventsManager()->fire('userDailyCounter:incrCommentLikeCount', $this, $user);
    }

    /**
     * @return EventsManager
     */
    protected function getPhEventsManager()
    {
        return Di::getDefault()->get('eventsManager');
    }

}
