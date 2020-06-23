<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\ImFriendGroup as ImFriendGroupModel;
use App\Repos\ImFriendGroup as ImFriendGroupRepo;
use App\Repos\ImFriendUser as ImFriendUserRepo;
use App\Repos\User as UserRepo;

class ImFriendUser extends Validator
{

    public function checkFriend($id)
    {
        $repo = new UserRepo();

        $user = $repo->findById($id);

        if (!$user) {
            throw new BadRequestException('im_friend_user.user_not_found');
        }

        return $user;
    }

    public function checkGroup($id)
    {
        /**
         * 返回默认分组
         */
        if (empty($id)) {
            $group = new ImFriendGroupModel();
            $group->id = 0;
            $group->name = '我的好友';
            return $group;
        }

        $repo = new ImFriendGroupRepo();

        $group = $repo->findById($id);

        if (!$group) {
            throw new BadRequestException('im_friend_user.group_not_found');
        }

        return $group;
    }

    public function checkRemark($remark)
    {
        $value = $this->filter->sanitize($remark, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 30) {
            throw new BadRequestException('im_friend_user.remark_too_long');
        }
    }

    public function checkIfSelfApply($userId, $friendId)
    {
        if ($userId == $friendId) {
            throw new BadRequestException('im_friend_user.self_apply');
        }
    }

    public function checkIfJoined($userId, $friendId)
    {
        $repo = new ImFriendUserRepo();

        $record = $repo->findFriendUser($friendId, $userId);

        if ($record && $record->blocked == 0) {
            throw new BadRequestException('im_friend_user.has_joined');
        }
    }

    public function checkIfBlocked($userId, $friendId)
    {
        $repo = new ImFriendUserRepo();

        $record = $repo->findFriendUser($friendId, $userId);

        if ($record && $record->blocked == 1) {
            throw new BadRequestException('im_friend_user.blocked');
        }
    }

}
