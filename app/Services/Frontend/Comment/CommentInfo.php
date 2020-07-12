<?php

namespace App\Services\Frontend\Comment;

use App\Models\Comment as CommentModel;
use App\Repos\User as UserRepo;
use App\Services\Frontend\CommentTrait;
use App\Services\Frontend\Service as FrontendService;

class CommentInfo extends FrontendService
{

    use CommentTrait;

    public function handle($id)
    {
        $comment = $this->checkComment($id);

        return $this->handleComment($comment);
    }

    protected function handleComment(CommentModel $comment)
    {
        $result = [
            'id' => $comment->id,
            'content' => $comment->content,
            'like_count' => $comment->like_count,
            'create_time' => $comment->create_time,
            'update_time' => $comment->update_time,
        ];

        $userRepo = new UserRepo();

        $owner = $userRepo->findById($comment->user_id);

        $result['user'] = [
            'id' => $owner->id,
            'name' => $owner->name,
            'avatar' => $owner->avatar,
        ];

        return $result;
    }

}
