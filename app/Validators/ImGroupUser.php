<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\ImGroupUser as ImGroupUserRepo;

class ImGroupUser extends Validator
{

    public function checkGroup($groupId)
    {
        $validator = new ImGroup();

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

    public function checkGroupUser($userId, $groupId)
    {
        $repo = new ImGroupUserRepo();

        $record = $repo->findGroupUser($userId, $groupId);

        if (!$record) {
            throw new BadRequestException('im_chat_group_user.not_found');
        }

        return $record;
    }

    public function checkIfJoined($userId, $groupId)
    {
        $repo = new ImGroupUserRepo();

        $record = $repo->findGroupUser($userId, $groupId);

        if ($record && $record->blocked == 0) {
            throw new BadRequestException('im_chat_group_user.has_joined');
        }
    }

    public function checkIfBlocked($userId, $groupId)
    {
        $repo = new ImGroupUserRepo();

        $record = $repo->findGroupUser($userId, $groupId);

        if ($record && $record->blocked == 1) {
            throw new BadRequestException('im_chat_group_user.blocked');
        }
    }

}
