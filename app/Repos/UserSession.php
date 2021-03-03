<?php

namespace App\Repos;

use App\Models\UserSession as UserSessionModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class UserSession extends Repository
{

    /**
     * @param int $userId
     * @return ResultsetInterface|Resultset|UserSessionModel[]
     */
    public function findByUserId($userId)
    {
        return UserSessionModel::query()
            ->where('user_id = :user_id:', ['user_id' => $userId])
            ->execute();
    }

}
