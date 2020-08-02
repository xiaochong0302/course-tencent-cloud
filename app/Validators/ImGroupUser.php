<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\ImGroupUser as ImGroupUserRepo;

class ImGroupUser extends Validator
{

    public function checkGroup($id)
    {
        $validator = new ImGroup();

        return $validator->checkGroup($id);
    }

    public function checkUser($id)
    {
        $validator = new ImUser();

        return $validator->checkUser($id);
    }

    public function checkRemark($remark)
    {
        $value = $this->filter->sanitize($remark, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 30) {
            throw new BadRequestException('im_group_user.remark_too_long');
        }

        return $remark;
    }

    public function checkGroupUser($groupId, $userId)
    {
        $repo = new ImGroupUserRepo();

        $record = $repo->findGroupUser($groupId, $userId);

        if (!$record) {
            throw new BadRequestException('im_group_user.not_found');
        }

        return $record;
    }

    public function checkIfJoined($groupId, $userId)
    {
        $repo = new ImGroupUserRepo();

        $record = $repo->findGroupUser($groupId, $userId);

        if ($record) {
            throw new BadRequestException('im_group_user.has_joined');
        }
    }

}
