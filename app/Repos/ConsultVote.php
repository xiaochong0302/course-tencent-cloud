<?php

namespace App\Repos;

use App\Models\ConsultVote as ConsultVoteModel;
use Phalcon\Mvc\Model;

class ConsultVote extends Repository
{

    /**
     * @param int $consultId
     * @param int $userId
     * @return ConsultVoteModel|Model|bool
     */
    public function findConsultVote($consultId, $userId)
    {
        $result = ConsultVoteModel::findFirst([
            'conditions' => 'consult_id = :consult_id: AND user_id = :user_id:',
            'bind' => ['consult_id' => $consultId, 'user_id' => $userId],
        ]);

        return $result;
    }

}
