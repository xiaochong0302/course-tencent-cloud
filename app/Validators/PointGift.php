<?php

namespace App\Validators;

use App\Caches\MaxPointGiftId as MaxPointGiftIdCache;
use App\Caches\PointGift as PointGiftCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Models\PointGift as PointGiftModel;
use App\Repos\PointGift as PointGiftRepo;

class PointGift extends Validator
{

    /**
     * @param int $id
     * @return PointGiftModel
     * @throws BadRequestException
     */
    public function checkGiftCache($id)
    {
        $this->checkId($id);

        $giftCache = new PointGiftCache();

        $gift = $giftCache->get($id);

        if (!$gift) {
            throw new BadRequestException('point_gift.not_found');
        }

        return $gift;
    }

    public function checkGift($id)
    {
        $this->checkId($id);

        $giftRepo = new PointGiftRepo();

        $gift = $giftRepo->findById($id);

        if (!$gift) {
            throw new BadRequestException('point_gift.not_found');
        }

        return $gift;
    }

    public function checkId($id)
    {
        $id = intval($id);

        $maxGiftIdCache = new MaxPointGiftIdCache();

        $maxId = $maxGiftIdCache->get();

        if ($id < 1 || $id > $maxId) {
            throw new BadRequestException('point_gift.not_found');
        }
    }

    public function checkName($name)
    {
        $value = $this->filter->sanitize($name, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('point_gift.name_too_short');
        }

        if ($length > 30) {
            throw new BadRequestException('point_gift.name_too_long');
        }

        return $value;
    }

    public function checkDetails($details)
    {
        $value = $this->filter->sanitize($details, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 30000) {
            throw new BadRequestException('point_gift.details_too_long');
        }

        return $value;
    }

    public function checkCover($cover)
    {
        $value = $this->filter->sanitize($cover, ['trim', 'string']);

        if (!CommonValidator::url($value)) {
            throw new BadRequestException('point_gift.invalid_cover');
        }

        return kg_cos_img_style_trim($value);
    }

    public function checkType($type)
    {
        $list = PointGiftModel::types();

        if (!isset($list[$type])) {
            throw new BadRequestException('point_gift.invalid_type');
        }

        return $type;
    }

    public function checkPoint($point)
    {
        $value = $this->filter->sanitize($point, ['trim', 'int']);

        if ($value < 1 || $value > 999999) {
            throw new BadRequestException('point_gift.invalid_point');
        }

        return $value;
    }

    public function checkStock($stock)
    {
        $value = $this->filter->sanitize($stock, ['trim', 'int']);

        if ($value < 1 || $value > 999999) {
            throw new BadRequestException('point_gift.invalid_stock');
        }

        return $value;
    }

    public function checkRedeemLimit($limit)
    {
        $value = $this->filter->sanitize($limit, ['trim', 'int']);

        if ($value < 1 || $value > 999999) {
            throw new BadRequestException('point_gift.invalid_redeem_limit');
        }

        return $value;
    }

    public function checkPublishStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('point_gift.invalid_publish_status');
        }

        return $status;
    }

    public function checkCourse($id)
    {
        $validator = new Course();

        return $validator->checkCourse($id);
    }

}
