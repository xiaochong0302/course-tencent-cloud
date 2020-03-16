<?php

namespace App\Repos;

use App\Models\ReviewVote as ReviewVoteModel;
use Phalcon\Mvc\Model;

class ReviewVote extends Repository
{

    /**
     * @param int $reviewId
     * @param int $userId
     * @return ReviewVoteModel|Model|bool
     */
    public function findReviewVote($reviewId, $userId)
    {
        $result = ReviewVoteModel::findFirst([
            'conditions' => 'review_id = :review_id: AND user_id = :user_id:',
            'bind' => ['review_id' => $reviewId, 'user_id' => $userId],
        ]);

        return $result;
    }

}
