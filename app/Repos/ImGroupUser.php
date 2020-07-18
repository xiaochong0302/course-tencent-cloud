<?php

namespace App\Repos;

use App\Models\ImGroupUser as ImGroupUserModel;
use Phalcon\Mvc\Model;

class ImGroupUser extends Repository
{

    /**
     * @param int $groupId
     * @param int $userId
     * @return ImGroupUserModel|Model|bool
     */
    public function findGroupUser($groupId, $userId)
    {
        return ImGroupUserModel::findFirst([
            'conditions' => 'group_id = ?1 AND user_id = ?2',
            'bind' => [1 => $groupId, 2 => $userId],
            'order' => 'id DESC',
        ]);
    }

}
