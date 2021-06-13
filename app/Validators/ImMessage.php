<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\ImMessage as ImMessageRepo;

class ImMessage extends Validator
{

    public function checkMessage($id)
    {
        $repo = new ImMessageRepo();

        $message = $repo->findById($id);

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

    public function checkIfSelfChat($fromId, $toId)
    {
        if ($fromId == $toId) {
            throw new BadRequestException('im_message.self_chat');
        }
    }

}
