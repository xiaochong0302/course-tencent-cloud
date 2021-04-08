<?php

namespace App\Repos;

use App\Models\CommentLike as CommentLikeModel;
use Phalcon\Mvc\Model;

class CommentLike extends Repository
{

    /**
     * @param int $commentId
     * @param int $userId
     * @return CommentLikeModel|Model|bool
     */
    public function findCommentLike($commentId, $userId)
    {
        return CommentLikeModel::findFirst([
            'conditions' => 'comment_id = :comment_id: AND user_id = :user_id:',
            'bind' => ['comment_id' => $commentId, 'user_id' => $userId],
        ]);
    }

}
