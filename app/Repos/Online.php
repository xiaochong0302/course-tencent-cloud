<?php

namespace App\Repos;

use App\Models\Online as OnlineModel;
use Phalcon\Mvc\Model;

class Online extends Repository
{

    /**
     * @param int $userId
     * @param string $activeDate
     * @return OnlineModel|Model|bool
     */
    public function findByUserDate($userId, $activeDate)
    {
        $activeTime = strtotime($activeDate);

        return OnlineModel::findFirst([
            'conditions' => 'user_id = ?1 AND active_time BETWEEN ?2 AND ?3',
            'bind' => [
                1 => $userId,
                2 => $activeTime,
                3 => $activeTime + 86400,
            ],
        ]);
    }

}