<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\ReviewLike as ReviewLikeModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;

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

    /**
     * @param int $userId
     * @return array
     */
    public function findUserLikedReviewIds($userId)
    {
        $result = [];

        /**
         * @var Resultset $rows
         */
        $rows = ReviewLikeModel::query()
            ->columns(['review_id'])
            ->where('user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('deleted = 0')
            ->execute();

        if ($rows->count() > 0) {
            $result = kg_array_column($rows->toArray(), 'review_id');
        }

        return $result;
    }

}
