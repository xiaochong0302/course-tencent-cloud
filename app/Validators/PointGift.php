<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Models\PointGift as PointGiftModel;
use App\Repos\PointGift as PointGiftRepo;
use App\Services\EditorStorage as EditorStorageService;

class PointGift extends Validator
{

    public function checkPointGift($id)
    {
        $giftRepo = new PointGiftRepo();

        $gift = $giftRepo->findById($id);

        if (!$gift) {
            throw new BadRequestException('point_gift.not_found');
        }

        return $gift;
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
        $value = $this->filter->sanitize($details, ['trim']);

        $storage = new EditorStorageService();

        $value = $storage->handle($value);

        $length = kg_editor_content_length($value);

        if ($length > 30000) {
            throw new BadRequestException('point_gift.details_too_long');
        }

        return $value;
    }

    public function checkCover($cover)
    {
        $value = $this->filter->sanitize($cover, ['trim', 'string']);

        if (!CommonValidator::image($value)) {
            throw new BadRequestException('point_gift.invalid_cover');
        }

        return kg_cos_img_style_trim($value);
    }

    public function checkAttrs(PointGiftModel $gift, array $attrs)
    {
        $result = $gift->attrs;

        if ($gift->type == PointGiftModel::TYPE_GOODS) {
            $result['url'] = $attrs['url'];
        }

        return $result;
    }

    public function checkType($type)
    {
        $list = PointGiftModel::types();

        if (!array_key_exists($type, $list)) {
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

        if ($value < 0 || $value > 999999) {
            throw new BadRequestException('point_gift.invalid_stock');
        }

        return $value;
    }

    public function checkRedeemLimit($limit)
    {
        $value = $this->filter->sanitize($limit, ['trim', 'int']);

        if ($value < 1 || $value > 10) {
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

    public function checkVip($id)
    {
        $validator = new Vip();

        return $validator->checkVip($id);
    }

}
