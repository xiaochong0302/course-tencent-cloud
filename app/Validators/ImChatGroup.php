<?php

namespace App\Validators;

use App\Caches\ImChatGroup as ImChatGroupCache;
use App\Caches\MaxImChatGroupId as MaxImChatGroupIdCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Models\ImChatGroup as ImChatGroupModel;
use App\Repos\ImChatGroup as ImChatGroupRepo;

class ImChatGroup extends Validator
{

    /**
     * @param int $id
     * @return ImChatGroupModel
     * @throws BadRequestException
     */
    public function checkGroupCache($id)
    {
        $id = intval($id);

        $maxGroupIdCache = new MaxImChatGroupIdCache();

        $maxGroupId = $maxGroupIdCache->get();

        /**
         * 防止缓存穿透
         */
        if ($id < 1 || $id > $maxGroupId) {
            throw new BadRequestException('im_chat_group.not_found');
        }

        $groupCache = new ImChatGroupCache();

        $group = $groupCache->get($id);

        if (!$group) {
            throw new BadRequestException('im_chat_group.not_found');
        }

        return $group;
    }

    public function checkGroup($id)
    {
        $groupRepo = new ImChatGroupRepo();

        $group = $groupRepo->findById($id);

        if (!$group) {
            throw new BadRequestException('im_chat_group.not_found');
        }

        return $group;
    }

    public function checkName($name)
    {
        $value = $this->filter->sanitize($name, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('im_chat_group.name_too_short');
        }

        if ($length > 50) {
            throw new BadRequestException('im_chat_group.name_too_long');
        }

        return $value;
    }

    public function checkAbout($name)
    {
        $value = $this->filter->sanitize($name, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 255) {
            throw new BadRequestException('im_chat_group.about_too_long');
        }

        return $value;
    }

}
