<?php

namespace App\Repos;

use App\Models\ImChatGroupUser as ImChatGroupUserModel;
use Phalcon\Mvc\Model;

class ImChatGroupUser extends Repository
{

    /**
     * @param int $userId
     * @param int $groupId
     * @return ImChatGroupUserModel|Model|bool
     */
    public function findGroupUser($userId, $groupId)
    {
        return ImChatGroupUserModel::findFirst([
            'conditions' => 'user_id = ?1 AND group_id = ?2',
            'bind' => [1 => $userId, 2 => $groupId],
            'order' => 'id DESC',
        ]);
    }

}
