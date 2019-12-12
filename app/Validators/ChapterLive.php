<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validator\Common as CommonValidator;

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
        $startTimeStamp = strtotime($startTime);
        $endTimeStamp = strtotime($endTime);

        if ($startTimeStamp < time()) {
            throw new BadRequestException('chapter_live.start_lt_now');
        }

        if ($endTimeStamp < time()) {
            throw new BadRequestException('chapter_live.end_lt_now');
        }

        if ($startTimeStamp >= $endTimeStamp) {
            throw new BadRequestException('chapter_live.start_gt_end');
        }

        if ($endTimeStamp - $startTimeStamp > 3 * 3600) {
            throw new BadRequestException('chapter_live.time_too_long');
        }
    }

}
