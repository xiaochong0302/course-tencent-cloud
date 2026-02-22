<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\AnswerLike as AnswerLikeModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class AnswerLike extends Repository
{

    /**
     * @param int $answerId
     * @param int $userId
     * @return AnswerLikeModel|Model|bool
     */
    public function findAnswerLike($answerId, $userId)
    {
        return AnswerLikeModel::findFirst([
            'conditions' => 'answer_id = :answer_id: AND user_id = :user_id:',
            'bind' => ['answer_id' => $answerId, 'user_id' => $userId],
        ]);
    }

    /**
     * @param int $userId
     * @return array
     */
    public function findUserLikedAnswerIds($userId)
    {
        $result = [];

        /**
         * @var Resultset $rows
         */
        $rows =  AnswerLikeModel::query()
            ->columns(['answer_id'])
            ->where('user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('deleted = 0')
            ->execute();

        if ($rows->count() > 0) {
            $result = kg_array_column($rows->toArray(), 'answer_id');
        }

        return $result;
    }

}
