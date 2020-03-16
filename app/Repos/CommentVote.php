<?php

namespace App\Repos;

use App\Models\CommentVote as CommentVoteModel;
use Phalcon\Mvc\Model;

class CommentVote extends Repository
{

    /**
     * @param int $commentId
     * @param int $userId
     * @return CommentVoteModel|Model|bool
     */
    public function findCommentVote($commentId, $userId)
    {
        $result = CommentVoteModel::findFirst([
            'conditions' => 'comment_id = :comment_id: AND user_id = :user_id:',
            'bind' => ['comment_id' => $commentId, 'user_id' => $userId],
        ]);

        return $result;
    }

}
