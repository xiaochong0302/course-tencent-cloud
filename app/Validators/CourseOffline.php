<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;

class CourseOffline extends Validator
{

    public function checkStartDate($startDate)
    {
        if (!CommonValidator::date($startDate)) {
            throw new BadRequestException('course_offline.invalid_start_date');
        }

        return $startDate;
    }

    public function checkEndDate($endDate)
    {
        if (!CommonValidator::date($endDate)) {
            throw new BadRequestException('course_offline.invalid_end_date');
        }

        return $endDate;
    }

    public function checkDateRange($startDate, $endDate)
    {
        if (strtotime($startDate) >= strtotime($endDate)) {
            throw new BadRequestException('course_offline.start_gt_end');
        }
    }

    public function checkUserLimit($limit)
    {
        $value = $this->filter->sanitize($limit, ['trim', 'int']);

        if ($value < 1 || $value > 999) {
            throw new BadRequestException('course_offline.invalid_user_limit');
        }

        return (int)$value;
    }

    public function checkLocation($location)
    {
        $value = $this->filter->sanitize($location, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 1 || $length > 50) {
            throw new BadRequestException('course_offline.invalid_location');
        }

        return $value;
    }

}
