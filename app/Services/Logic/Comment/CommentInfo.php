<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Comment;

use App\Models\Comment as CommentModel;
use App\Models\User as UserModel;
use App\Repos\AnswerLike as AnswerLikeRepo;
use App\Services\Logic\CommentTrait;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\UserTrait;

class CommentInfo extends LogicService
{

    use CommentTrait;
    use UserTrait;

    public function handle($id)
    {
        $comment = $this->checkComment($id);

        $user = $this->getCurrentUser(true);

        return $this->handleComment($comment, $user);
    }

    protected function handleComment(CommentModel $comment, UserModel $user)
    {
        $toUser = $this->handleShallowUserInfo($comment->to_user_id);
        $owner = $this->handleShallowUserInfo($comment->owner_id);
        $me = $this->handleMeInfo($comment, $user);

        return [
            'id' => $comment->id,
            'content' => $comment->content,
            'published' => $comment->published,
            'deleted' => $comment->deleted,
            'parent_id' => $comment->parent_id,
            'like_count' => $comment->like_count,
            'reply_count' => $comment->reply_count,
            'create_time' => $comment->create_time,
            'update_time' => $comment->update_time,
            'to_user' => $toUser,
            'owner' => $owner,
            'me' => $me,
        ];
    }

    protected function handleMeInfo(CommentModel $comment, UserModel $user)
    {
        $me = [
            'liked' => 0,
            'owned' => 0,
        ];

        if ($user->id == $comment->owner_id) {
            $me['owned'] = 1;
        }

        if ($user->id > 0) {

            $likeRepo = new AnswerLikeRepo();

            $like = $likeRepo->findAnswerLike($comment->id, $user->id);

            if ($like && $like->deleted == 0) {
                $me['liked'] = 1;
            }
        }

        return $me;
    }

}
