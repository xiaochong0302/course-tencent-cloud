<?php

namespace App\Services\Frontend\Comment;

use App\Services\Frontend\CommentTrait;
use App\Services\Frontend\Service;
use App\Validators\Comment as CommentValidator;

class CommentUpdate extends Service
{

    use CommentTrait;

    public function handle($id)
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $comment = $this->checkComment($id);

        $validator = new CommentValidator();

        $validator->checkOwner($user->id, $comment->user_id);

        $data = [];

        $data['content'] = $validator->checkContent($post['content']);

        if (isset($post['mentions'])) {
            $data['mentions'] = $validator->checkMentions($post['mentions']);
        }

        $comment->update($data);
    }

    protected function handleMentions($mentions)
    {

    }

}
