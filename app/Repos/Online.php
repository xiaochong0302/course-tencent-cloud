<?php

namespace App\Repos;

use App\Models\Online as OnlineModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Online extends Repository
{

    /**
     * @param int $userId
     * @param string $activeDate
     * @return ResultsetInterface|Resultset|OnlineModel[]
     */
    public function findByUserDate($userId, $activeDate)
    {
        $startTime = strtotime($activeDate);

        $endTime = $startTime + 86400;

        return OnlineModel::query()
            ->where('user_id = :user_id:', ['user_id' => $userId])
            ->betweenWhere('active_time', $startTime, $endTime)
            ->execute();
    }

}