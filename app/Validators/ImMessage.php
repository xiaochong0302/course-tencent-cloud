<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\ImFriendMessage as ImFriendMessageRepo;
use App\Repos\ImGroupMessage as ImGroupMessageRepo;
use App\Repos\ImSystemMessage as ImSystemMessageRepo;
use App\Validators\ImChatGroup as ImChatGroupValidator;
use App\Validators\ImChatGroupUser as ImChatGroupUserValidator;
use App\Validators\ImFriendUser as ImFriendUserValidator;
use App\Validators\User as UserValidator;

class ImMessage extends Validator
{

    public function checkMessage($id, $type)
    {
        $this->checkType($type);

        $message = null;

        if ($type == 'friend') {
            $repo = new ImFriendMessageRepo();
            $message = $repo->findById($id);
        } elseif ($type == 'group') {
            $repo = new ImGroupMessageRepo();
            $message = $repo->findById($id);
        } elseif ($type == 'system') {
            $repo = new ImSystemMessageRepo();
            $message = $repo->findById($id);
        }

        if (!$message) {
            throw new BadRequestException('im_message.not_found');
        }

        return $message;
    }

    public function checkReceiver($id, $type)
    {
        $this->checkType($type);

        $receiver = null;

        if ($type == 'friend') {
            $validator = new UserValidator();
            $receiver = $validator->checkUserCache($id);
        } elseif ($type == 'group') {
            $validator = new ImChatGroupValidator();
            $receiver = $validator->checkGroupCache($id);
        }

        return $receiver;
    }

    public function checkType($type)
    {
        if (!in_array($type, ['friend', 'group', 'system'])) {
            throw new BadRequestException('im_message.invalid_type');
        }

        return $type;
    }

    public function checkContent($content)
    {
        $value = $this->filter->sanitize($content, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 1) {
            throw new BadRequestException('im_message.content_too_short');
        }

        if ($length > 1000) {
            throw new BadRequestException('im_message.content_too_long');
        }

        return $value;
    }

    public function checkIfBlocked($userId, $targetId, $targetType)
    {
        if ($targetType == 'friend') {
            $validator = new ImFriendUserValidator();
            $validator->checkIfBlocked($userId, $targetId);
        } elseif ($targetType == 'group') {
            $validator = new ImChatGroupUserValidator();
            $validator->checkIfBlocked($userId, $targetId);
        }
    }

}
