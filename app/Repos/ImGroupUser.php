<?php

namespace App\Repos;

use App\Models\ImGroupUser as ImGroupUserModel;
use Phalcon\Mvc\Model;

class ImGroupUser extends Repository
{

    /**
     * @param int $userId
     * @param int $groupId
     * @return ImGroupUserModel|Model|bool
     */
    public function findGroupUser($userId, $groupId)
    {
        return ImGroupUserModel::findFirst([
            'conditions' => 'user_id = ?1 AND group_id = ?2',
            'bind' => [1 => $userId, 2 => $groupId],
            'order' => 'id DESC',
        ]);
    }

}
