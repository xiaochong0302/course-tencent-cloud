<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Order;

use App\Models\Course as CourseModel;
use App\Models\User as UserModel;

class CoursePayCalculator extends PayCalculator
{

    /**
     * @var CourseModel
     */
    protected CourseModel $course;

    public function __construct(CourseModel $course)
    {
        $this->course = $course;
    }

    public function handleNormalPay(UserModel $user): void
    {
        $this->totalAmount = $this->course->market_price;
        $this->payAmount = $this->course->market_price;

        if ($user->vip == 1) {
            $this->payAmount = $this->course->vip_price;
        }

        $this->discountAmount = $this->totalAmount - $this->payAmount;
    }

}
