<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;

class ChapterLive extends Validator
{

    public function checkStartTime($startTime)
    {
        if (!CommonValidator::date($startTime, 'Y-m-d H:i:s')) {
            throw new BadRequestException('chapter_live.invalid_start_time');
        }

        return strtotime($startTime);
    }

    public function checkEndTime($endTime)
    {
        if (!CommonValidator::date($endTime, 'Y-m-d H:i:s')) {
            throw new BadRequestException('chapter_live.invalid_end_time');
        }

        return strtotime($endTime);
    }

    public function checkTimeRange($startTime, $endTime)
    {
        if ($startTime < time()) {
            throw new BadRequestException('chapter_live.start_lt_now');
        }

        if ($startTime < time()) {
            throw new BadRequestException('chapter_live.end_lt_now');
        }

        if ($startTime >= $endTime) {
            throw new BadRequestException('chapter_live.start_gt_end');
        }

        if ($endTime - $startTime > 3 * 3600) {
            throw new BadRequestException('chapter_live.time_too_long');
        }
    }

}
