<?php

namespace App\Repos;

use App\Models\ImChatGroupUser as ImChatGroupUserModel;
use Phalcon\Mvc\Model;

class ImChatGroupUser extends Repository
{

    /**
     * @param int $groupId
     * @param int $userId
     * @return ImChatGroupUserModel|Model|bool
     */
    public function findGroupUser($groupId, $userId)
    {
        return ImChatGroupUserModel::findFirst([
            'conditions' => 'group_id = ?1 AND user_id = ?2',
            'bind' => [1 => $groupId, 2 => $userId],
            'order' => 'id DESC',
        ]);
    }

}
