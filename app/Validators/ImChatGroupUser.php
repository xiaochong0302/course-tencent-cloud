<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\ImChatGroupUser as ImChatGroupUserRepo;

class ImChatGroupUser extends Validator
{

    public function checkGroup($groupId)
    {
        $validator = new ImChatGroup();

        return $validator->checkGroup($groupId);
    }

    public function checkRemark($remark)
    {
        $value = $this->filter->sanitize($remark, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 30) {
            throw new BadRequestException('im_chat_group_user.remark_too_long');
        }

        return $remark;
    }

    public function checkIfJoined($userId, $groupId)
    {
        $repo = new ImChatGroupUserRepo();

        $record = $repo->findGroupUser($userId, $groupId);

        if ($record && $record->blocked == 0) {
            throw new BadRequestException('im_chat_group_user.has_joined');
        }
    }

    public function checkIfBlocked($userId, $groupId)
    {
        $repo = new ImChatGroupUserRepo();

        $record = $repo->findGroupUser($userId, $groupId);

        if ($record && $record->blocked == 1) {
            throw new BadRequestException('im_chat_group_user.blocked');
        }
    }

}
