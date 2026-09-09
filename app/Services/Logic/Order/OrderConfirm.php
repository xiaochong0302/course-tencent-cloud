<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Order;

use App\Models\Course as CourseModel;
use App\Models\KgProduct as KgProductModel;
use App\Models\Vip as VipModel;
use App\Services\Logic\Service as LogicService;
use App\Validators\Order as OrderValidator;

class OrderConfirm extends LogicService
{

    public function handle(int $itemId, int $itemType): array
    {
        $user = $this->getLoginUser();

        $validator = new OrderValidator();

        $validator->checkItemType($itemType);

        $result = [];

        $result['total_amount'] = 0.00;
        $result['discount_amount'] = 0.00;
        $result['pay_amount'] = 0.00;

        $result['item_id'] = $itemId;
        $result['item_type'] = $itemType;
        $result['item_info'] = [];

        $calculator = null;

        if ($itemType == KgProductModel::ITEM_COURSE) {

            $course = $validator->checkCourse($itemId);

            $result['item_info']['course'] = $this->handleCourseInfo($course);

            $calculator = new CoursePayCalculator($course);

            $calculator->handleNormalPay($user);

        } elseif ($itemType == KgProductModel::ITEM_VIP) {

            $vip = $validator->checkVip($itemId);

            $result['item_info']['vip'] = $this->handleVipInfo($vip);

            $calculator = new VipPayCalculator($vip);

            $calculator->handleNormalPay($user);
        }

        if ($calculator) {
            $result['total_amount'] = $calculator->totalAmount;
            $result['pay_amount'] = $calculator->payAmount;
            $result['discount_amount'] = $calculator->discountAmount;
        }

        return $result;
    }

    protected function handleCourseInfo(CourseModel $course): array
    {
        return $this->formatCourseInfo($course);
    }

    protected function handleVipInfo(VipModel $vip): array
    {
        return [
            'id' => $vip->id,
            'title' => $vip->title,
            'cover' => $vip->cover,
            'expiry' => $vip->expiry,
            'price' => $vip->price,
        ];
    }

    protected function formatCourseInfo(CourseModel $course): array
    {
        return [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $course->cover,
            'model' => $course->model,
            'level' => $course->level,
            'attrs' => $course->attrs,
            'user_count' => $course->getUserCount(),
            'lesson_count' => $course->lesson_count,
            'study_expiry' => $course->study_expiry,
            'refund_expiry' => $course->refund_expiry,
            'market_price' => $course->market_price,
            'vip_price' => $course->vip_price,
        ];
    }

}
