<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Danmu as DanmuModel;
use App\Repos\Danmu as DanmuRepo;

class Danmu extends Validator
{

    public function checkDanmu($id)
    {
        $danmuRepo = new DanmuRepo();

        $danmu = $danmuRepo->findById($id);

        if (!$danmu) {
            throw new BadRequestException('danmu.not_found');
        }

        return $danmu;
    }

    public function checkChapter($id)
    {
        $validator = new Chapter();

        return $validator->checkChapter($id);
    }

    public function checkText($text)
    {
        $value = $this->filter->sanitize($text, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 1) {
            throw new BadRequestException('danmu.text_too_short');
        }

        if ($length > 100) {
            throw new BadRequestException('danmu.text_too_long');
        }

        return $value;
    }

    public function checkSize($size)
    {
        $list = DanmuModel::sizeTypes();

        if (!isset($list[$size])) {
            throw new BadRequestException('danmu.invalid_size');
        }

        return $size;
    }

    public function checkPosition($pos)
    {
        $list = DanmuModel::positionTypes();

        if (!isset($list[$pos])) {
            throw new BadRequestException('danmu.invalid_position');
        }

        return $pos;
    }

    public function checkTime($time)
    {
        $value = (int)$time;

        if ($value < 0) {
            throw new BadRequestException('danmu.invalid_time');
        }

        if ($value > 3 * 3600) {
            throw new BadRequestException('danmu.invalid_time');
        }

        return $value;
    }

}
