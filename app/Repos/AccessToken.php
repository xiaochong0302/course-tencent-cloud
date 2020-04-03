<?php

namespace App\Repos;

use App\Models\AccessToken as AccessTokenModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class AccessToken extends Repository
{

    /**
     * @param int $id
     * @return AccessTokenModel|Model|bool
     */
    public function findById($id)
    {
        return AccessTokenModel::findFirst($id);
    }

    /**
     * @param int $userId
     * @return ResultsetInterface|Resultset|AccessTokenModel[]
     */
    public function findByUserId($userId)
    {
        return AccessTokenModel::query()
            ->where('user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('deleted = 0')
            ->execute();
    }

    public function countByUserId($userId)
    {
        return AccessTokenModel::count([
            'conditions' => 'user_id = :user_id: AND deleted = 0',
            'bind' => ['user_id' => $userId],
        ]);
    }

}
