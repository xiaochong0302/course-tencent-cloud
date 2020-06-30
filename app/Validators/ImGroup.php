<?php

namespace App\Validators;

use App\Caches\ImGroup as ImGroupCache;
use App\Caches\MaxImGroupId as MaxImGroupIdCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Models\ImGroup as ImGroupModel;
use App\Repos\ImGroup as ImGroupRepo;

class ImGroup extends Validator
{

    /**
     * @param int $id
     * @return ImGroupModel
     * @throws BadRequestException
     */
    public function checkGroupCache($id)
    {
        $id = intval($id);

        $maxGroupIdCache = new MaxImGroupIdCache();

        $maxGroupId = $maxGroupIdCache->get();

        /**
         * 防止缓存穿透
         */
        if ($id < 1 || $id > $maxGroupId) {
            throw new BadRequestException('im_chat_group.not_found');
        }

        $groupCache = new ImGroupCache();

        $group = $groupCache->get($id);

        if (!$group) {
            throw new BadRequestException('im_chat_group.not_found');
        }

        return $group;
    }

    public function checkGroup($id)
    {
        $groupRepo = new ImGroupRepo();

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

        if ($length > 30) {
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
