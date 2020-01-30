<?php

namespace App\Repos;

use App\Models\CommentLike as CommentLikeModel;

class CommentLike extends Repository
{

    public function findCommentLike($commentId, $userId)
    {
        $result = CommentLikeModel::query()
            ->where('comment_id = :comment_id:', ['comment_id' => $commentId])
            ->amdWhere('user_id = :user_id:', ['user_id' => $userId])
            ->execute()->getFirst();

        return $result;
    }

}
