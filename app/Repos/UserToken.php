<?php

namespace App\Repos;

use App\Models\UserToken as UserTokenModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class UserToken extends Repository
{

    /**
     * @param int $userId
     * @return ResultsetInterface|Resultset|UserTokenModel[]
     */
    public function findByUserId($userId)
    {
        return UserTokenModel::query()
            ->where('user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('deleted = 0')
            ->execute();
    }

}
