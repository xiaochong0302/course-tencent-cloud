<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\ImFriendGroup as ImFriendGroupModel;
use App\Repos\ImFriendUser as ImFriendUserRepo;

class ImFriendUser extends Validator
{

    public function checkFriend($id)
    {
        $validator = new ImUser();

        return $validator->checkUser($id);
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

        $validator = new ImFriendGroup();

        $group = $validator->checkGroup($id);

        return $group;
    }

    public function checkRemark($remark)
    {
        $value = $this->filter->sanitize($remark, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 30) {
            throw new BadRequestException('im_friend_user.remark_too_long');
        }

        return $remark;
    }

    public function checkFriendUser($userId, $friendId)
    {
        $repo = new ImFriendUserRepo();

        $record = $repo->findFriendUser($userId, $friendId);

        if (!$record) {
            throw new BadRequestException('im_friend_user.not_found');
        }

        return $record;
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

        if ($record) {
            throw new BadRequestException('im_friend_user.has_joined');
        }
    }

}
