<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Comment;

use App\Models\Comment as CommentModel;
use App\Repos\User as UserRepo;
use App\Services\Logic\CommentTrait;
use App\Services\Logic\Service as LogicService;

class CommentInfo extends LogicService
{

    use CommentTrait;

    public function handle($id)
    {
        $comment = $this->checkComment($id);

        return $this->handleComment($comment);
    }

    protected function handleComment(CommentModel $comment)
    {
        $owner = $comment->owner_id > 0 ? $this->handleOwnerInfo($comment) : new \stdClass();
        $toUser = $comment->to_user_id > 0 ? $this->handleToUserInfo($comment) : new \stdClass();

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
            'owner' => $owner,
            'to_user' => $toUser,
        ];
    }

    protected function handleOwnerInfo(CommentModel $comment)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($comment->owner_id);

        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
        ];
    }

    protected function handleToUserInfo(CommentModel $comment)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($comment->to_user_id);

        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
        ];
    }

}
