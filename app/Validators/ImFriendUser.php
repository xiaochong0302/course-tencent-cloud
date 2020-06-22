<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\ImFriendUser as ImFriendUserRepo;

class ImFriendUser extends Validator
{

    public function checkIfSelfApply($userId, $friendId)
    {
        if ($userId == $friendId) {
            throw new BadRequestException('im_friend_user.self_apply');
        }
    }

    public function checkIfJoined($userId, $friendId)
    {
        $repo = new ImFriendUserRepo();

        $record = $repo->findFriendUser($userId, $friendId);

        if ($record && $record->blocked == 0) {
            throw new BadRequestException('im_friend_user.has_joined');
        }
    }

    public function checkIfBlocked($userId, $friendId)
    {
        $repo = new ImFriendUserRepo();

        $record = $repo->findFriendUser($userId, $friendId);

        if ($record && $record->blocked == 1) {
            throw new BadRequestException('im_friend_user.blocked');
        }
    }

}
