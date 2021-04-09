<?php

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
        $owner = $this->handleOwnerInfo($comment);

        return [
            'id' => $comment->id,
            'owner' => $owner,
            'content' => $comment->content,
            'create_time' => $comment->create_time,
        ];
    }

    protected function handleOwnerInfo(CommentModel $comment)
    {
        $userRepo = new UserRepo();

        $owner = $userRepo->findById($comment->owner_id);

        return [
            'id' => $owner->id,
            'name' => $owner->name,
            'avatar' => $owner->avatar,
        ];
    }

}
