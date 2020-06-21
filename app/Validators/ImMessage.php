<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\ImFriendMessage as ImFriendMessageRepo;
use App\Repos\ImGroupMessage as ImGroupMessageRepo;

class ImMessage extends Validator
{

    public function checkFriendMessage($id)
    {
        $messageRepo = new ImFriendMessageRepo();

        $message = $messageRepo->findById($id);

        if (!$message) {
            throw new BadRequestException('im_message.not_found');
        }

        return $message;
    }

    public function checkGroupMessage($id)
    {
        $messageRepo = new ImGroupMessageRepo();

        $message = $messageRepo->findById($id);

        if (!$message) {
            throw new BadRequestException('im_message.not_found');
        }

        return $message;
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
