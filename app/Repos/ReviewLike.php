<?php

namespace App\Repos;

use App\Models\ReviewLike as ReviewLikeModel;
use Phalcon\Mvc\Model;

class ReviewLike extends Repository
{

    /**
     * @param int $reviewId
     * @param int $userId
     * @return ReviewLikeModel|Model|bool
     */
    public function findReviewLike($reviewId, $userId)
    {
        return ReviewLikeModel::findFirst([
            'conditions' => 'review_id = :review_id: AND user_id = :user_id:',
            'bind' => ['review_id' => $reviewId, 'user_id' => $userId],
        ]);
    }

}
