<?php

namespace App\Repos;

use App\Models\ImFriendUser as ImFriendUserModel;
use Phalcon\Mvc\Model;

class ImFriendUser extends Repository
{

    /**
     * @param int $userId
     * @param int $friendId
     * @return ImFriendUserModel|Model|bool
     */
    public function findFriendUser($userId, $friendId)
    {
        return ImFriendUserModel::findFirst([
            'conditions' => 'user_id = ?1 AND friend_id = ?2',
            'bind' => [1 => $userId, 2 => $friendId],
            'order' => 'id DESC',
        ]);
    }

}
