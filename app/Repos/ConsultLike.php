<?php

namespace App\Repos;

use App\Models\ConsultLike as ConsultLikeModel;
use Phalcon\Mvc\Model;

class ConsultLike extends Repository
{

    /**
     * @param int $consultId
     * @param int $userId
     * @return ConsultLikeModel|Model|bool
     */
    public function findConsultLike($consultId, $userId)
    {
        return ConsultLikeModel::findFirst([
            'conditions' => 'consult_id = :consult_id: AND user_id = :user_id:',
            'bind' => ['consult_id' => $consultId, 'user_id' => $userId],
        ]);
    }

}
