<?php

namespace App\Caches;

use App\Repos\Comment as CommentRepo;

class CommentCounter extends Counter
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "comment_counter:{$id}";
    }

    public function getContent($id = null)
    {
        $commentRepo = new CommentRepo();

        $comment = $commentRepo->findById($id);

        if (!$comment) return null;

        return [
            'reply_count' => $comment->reply_count,
            'like_count' => $comment->like_count,
        ];
    }

}
