<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Models\Vip as VipModel;
use App\Repos\Vip as VipRepo;

class Vip extends Validator
{

    public function checkVip(int $id): VipModel
    {
        $vipRepo = new VipRepo();

        $vip = $vipRepo->findById($id);

        if (!$vip) {
            throw new BadRequestException('vip.not_found');
        }

        return $vip;
    }

    public function checkTitle(string $title): string
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('vip.title_too_short');
        }

        if ($length > 30) {
            throw new BadRequestException('vip.title_too_long');
        }

        return $value;
    }

    public function checkCover(string $cover): string
    {
        $value = $this->filter->sanitize($cover, ['trim', 'string']);

        if (!CommonValidator::image($value)) {
            throw new BadRequestException('vip.invalid_cover');
        }

        return kg_cos_img_style_trim($value);
    }

    public function checkExpiry(int $expiry): int
    {
        $value = $this->filter->sanitize($expiry, ['trim', 'int']);

        if ($value < 1 || $value > 60) {
            throw new BadRequestException('vip.invalid_expiry');
        }

        return $value;
    }

    public function checkPrice(float $price): float
    {
        $value = $this->filter->sanitize($price, ['trim', 'float']);

        if ($value < 1 || $value > 999999) {
            throw new BadRequestException('vip.invalid_price');
        }

        return $value;
    }

    public function checkPublishStatus(int $status): int
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('vip.invalid_publish_status');
        }

        return $status;
    }

}
