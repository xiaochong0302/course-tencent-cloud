<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Comment;

use App\Models\Comment as CommentModel;
use App\Models\User as UserModel;
use App\Repos\CommentLike as CommentLikeRepo;
use App\Services\Logic\CommentTrait;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\User\ShallowUserInfo;
use App\Services\Logic\UserTrait;

class CommentInfo extends LogicService
{

    use CommentTrait;
    use UserTrait;

    public function handle($id)
    {
        $comment = $this->checkComment($id);

        $user = $this->getCurrentUser();

        return $this->handleComment($comment, $user);
    }

    protected function handleComment(CommentModel $comment, UserModel $user)
    {
        $toUser = $this->handleToUserInfo($comment->to_user_id);
        $owner = $this->handleOwnerInfo($comment->owner_id);
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

    protected function handleToUserInfo($userId)
    {
        if ($userId == 0) return null;

        $service = new ShallowUserInfo();

        return $service->handle($userId);
    }

    protected function handleOwnerInfo($userId)
    {
        $service = new ShallowUserInfo();

        return $service->handle($userId);
    }

    protected function handleMeInfo(CommentModel $comment, UserModel $user)
    {
        $me = [
            'logged' => 0,
            'liked' => 0,
            'owned' => 0,
        ];

        if ($user->id == $comment->owner_id) {
            $me['owned'] = 1;
        }

        if ($user->id > 0) {

            $me['logged'] = 1;

            $likeRepo = new CommentLikeRepo();

            $like = $likeRepo->findCommentLike($comment->id, $user->id);

            if ($like && $like->deleted == 0) {
                $me['liked'] = 1;
            }
        }

        return $me;
    }

}
