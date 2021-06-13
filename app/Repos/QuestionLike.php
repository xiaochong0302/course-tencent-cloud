<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\QuestionLike as QuestionLikeModel;
use Phalcon\Mvc\Model;

class QuestionLike extends Repository
{

    /**
     * @param int $questionId
     * @param int $userId
     * @return QuestionLikeModel|Model|bool
     */
    public function findQuestionLike($questionId, $userId)
    {
        return QuestionLikeModel::findFirst([
            'conditions' => 'question_id = :question_id: AND user_id = :user_id:',
            'bind' => ['question_id' => $questionId, 'user_id' => $userId],
        ]);
    }

}
