<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\CommentLike as CommentLikeModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

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

    /**
     * @param int $userId
     * @return array
     */
    public function findUserLikedCommentIds($userId)
    {
        $result = [];

        /**
         * @var Resultset $rows
         */
        $rows =  CommentLikeModel::query()
            ->columns(['comment_id'])
            ->where('user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('deleted = 0')
            ->execute();

        if ($rows->count() > 0) {
            $result = kg_array_column($rows->toArray(), 'comment_id');
        }

        return $result;
    }

}
