<?php

namespace App\Validators;

use App\Caches\ImChatGroup as ImChatGroupCache;
use App\Caches\User as UserCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\ImFriendMessage as ImFriendMessageRepo;
use App\Repos\ImGroupMessage as ImGroupMessageRepo;

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
            $cache = new UserCache();
            $receiver = $cache->get($id);
            if (!$receiver) {
                throw new BadRequestException('user.not_found');
            }
        } elseif ($type == 'group') {
            $cache = new ImChatGroupCache();
            $receiver = $cache->get($id);
            if (!$receiver) {
                throw new BadRequestException('im_chat_group.not_found');
            }
        }

        return $receiver;
    }

    public function checkType($type)
    {
        if (!in_array($type, ['friend', 'group'])) {
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

}
