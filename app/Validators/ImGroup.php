<?php

namespace App\Validators;

use App\Caches\ImGroup as ImGroupCache;
use App\Caches\MaxImGroupId as MaxImGroupIdCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
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
        $this->checkId($id);

        $groupCache = new ImGroupCache();

        $group = $groupCache->get($id);

        if (!$group) {
            throw new BadRequestException('im_group.not_found');
        }

        return $group;
    }

    public function checkGroup($id)
    {
        $this->checkId($id);

        $groupRepo = new ImGroupRepo();

        $group = $groupRepo->findById($id);

        if (!$group) {
            throw new BadRequestException('im_group.not_found');
        }

        return $group;
    }

    public function checkId($id)
    {
        $id = intval($id);

        $maxGroupIdCache = new MaxImGroupIdCache();

        $maxId = $maxGroupIdCache->get();

        if ($id < 1 || $id > $maxId) {
            throw new BadRequestException('im_group.not_found');
        }
    }

    public function checkName($name)
    {
        $value = $this->filter->sanitize($name, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('im_group.name_too_short');
        }

        if ($length > 30) {
            throw new BadRequestException('im_group.name_too_long');
        }

        return $value;
    }

    public function checkAbout($name)
    {
        $value = $this->filter->sanitize($name, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 255) {
            throw new BadRequestException('im_group.about_too_long');
        }

        return $value;
    }

    public function checkAvatar($avatar)
    {
        $value = $this->filter->sanitize($avatar, ['trim', 'string']);

        if (!CommonValidator::url($value)) {
            throw new BadRequestException('im_group.invalid_avatar');
        }

        return kg_cos_img_style_trim($value);
    }

    public function checkType($type)
    {
        $list = ImGroupModel::types();

        if (!isset($list[$type])) {
            throw new BadRequestException('im_group.invalid_type');
        }

        return $type;
    }

    public function checkPublishStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('im_group.invalid_publish_status');
        }

        return $status;
    }

    public function checkGroupOwner($userId)
    {
        $validator = new User();

        return $validator->checkUser($userId);
    }

}
