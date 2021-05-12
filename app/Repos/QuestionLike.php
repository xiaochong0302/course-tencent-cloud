<?php

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
