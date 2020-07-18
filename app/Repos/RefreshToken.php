<?php

namespace App\Repos;

use App\Models\RefreshToken as RefreshTokenModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class RefreshToken extends Repository
{

    /**
     * @param string $id
     * @return RefreshTokenModel|Model|bool
     */
    public function findById($id)
    {
        return RefreshTokenModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param int $userId
     * @return ResultsetInterface|Resultset|RefreshTokenModel[]
     */
    public function findByUserId($userId)
    {
        return RefreshTokenModel::query()
            ->where('user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('deleted = 0')
            ->execute();
    }

    public function countByUserId($userId)
    {
        return (int)RefreshTokenModel::count([
            'conditions' => 'user_id = :user_id: AND deleted = 0',
            'bind' => ['user_id' => $userId],
        ]);
    }

}
