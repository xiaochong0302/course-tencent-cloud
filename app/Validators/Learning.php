<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;

class Learning extends Validator
{

    public function checkPlanId($planId)
    {
        if (!CommonValidator::date($planId, 'Ymd')) {
            throw new BadRequestException('learning.invalid_plan_id');
        }

        return $planId;
    }

    public function checkRequestId($requestId)
    {
        if (!$requestId) {
            throw new BadRequestException('learning.invalid_request_id');
        }

        return $requestId;
    }

    public function checkInterval($interval)
    {
        $value = $this->filter->sanitize($interval, ['trim', 'int']);

        /**
         * 兼容秒和毫秒
         */
        if ($value > 1000) {
            $value = intval($value / 1000);
        }

        if ($value < 5) {
            throw new BadRequestException('learning.invalid_interval');
        }

        return $value;
    }

    public function checkPosition($position)
    {
        $value = $this->filter->sanitize($position, ['trim', 'float']);

        if ($value < 0 || $value > 3 * 3600) {
            throw new BadRequestException('learning.invalid_position');
        }

        return $value;
    }

}
