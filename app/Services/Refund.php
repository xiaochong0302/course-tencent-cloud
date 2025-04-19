<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services;

use App\Models\Order as OrderModel;
use App\Models\Trade as TradeModel;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\Order as OrderRepo;

class Refund extends Service
{

    public function preview(OrderModel $order)
    {
        $result = [
            'item_type' => 0,
            'item_info' => [],
            'refund_amount' => 0.00,
            'service_fee' => 0.00,
            'service_rate' => 5.00,
        ];

        switch ($order->item_type) {
            case OrderModel::ITEM_COURSE:
                $result = $this->previewCourseRefund($order);
                break;
            case OrderModel::ITEM_PACKAGE:
                $result = $this->previewPackageRefund($order);
                break;
            case OrderModel::ITEM_VIP:
                $result = $this->previewVipRefund($order);
                break;
            case OrderModel::ITEM_TEST:
                $result = $this->previewTestRefund($order);
                break;
        }

        return $result;
    }

    protected function previewCourseRefund(OrderModel $order)
    {
        $itemInfo = $order->item_info;

        $itemInfo['course']['cover'] = kg_cos_course_cover_url($itemInfo['course']['cover']);

        $serviceFee = $this->getServiceFee($order);
        $serviceRate = $this->getServiceRate($order);

        $refundRate = 0.00;
        $refundAmount = 0.00;

        if ($itemInfo['course']['refund_expiry_time'] > time()) {
            $refundRate = $this->getCourseRefundRate($order->item_id, $order->owner_id);
            $refundAmount = round(($order->amount - $serviceFee) * $refundRate, 2);
        }

        $itemInfo['course']['refund_rate'] = $refundRate;
        $itemInfo['course']['refund_amount'] = $refundAmount;

        return [
            'item_type' => $order->item_type,
            'item_info' => $itemInfo,
            'refund_amount' => $refundAmount,
            'service_fee' => $serviceFee,
            'service_rate' => $serviceRate,
        ];
    }

    protected function previewPackageRefund(OrderModel $order)
    {
        $itemInfo = $order->item_info;

        $serviceFee = $this->getServiceFee($order);
        $serviceRate = $this->getServiceRate($order);

        $totalMarketPrice = 0.00;

        foreach ($itemInfo['courses'] as $course) {
            $totalMarketPrice += $course['market_price'];
        }

        $totalRefundAmount = 0.00;

        /**
         * 按照占比方式计算退款
         */
        foreach ($itemInfo['courses'] as &$course) {

            $course['cover'] = kg_cos_course_cover_url($course['cover']);

            $refundRate = 0.00;
            $refundAmount = 0.00;

            if ($course['refund_expiry_time'] > time()) {
                $priceRate = round($course['market_price'] / $totalMarketPrice, 4);
                $refundRate = $this->getCourseRefundRate($course['id'], $order->owner_id);
                $refundAmount = round(($order->amount - $serviceFee) * $priceRate * $refundRate, 2);
                $totalRefundAmount += $refundAmount;
            }

            $course['refund_rate'] = $refundRate;
            $course['refund_amount'] = $refundAmount;
        }

        return [
            'item_type' => $order->item_type,
            'item_info' => $itemInfo,
            'refund_amount' => $totalRefundAmount,
            'service_fee' => $serviceFee,
            'service_rate' => $serviceRate,
        ];
    }

    protected function previewVipRefund(OrderModel $order)
    {
        return $this->previewOtherRefund($order);
    }

    protected function previewTestRefund(OrderModel $order)
    {
        return $this->previewOtherRefund($order);
    }

    protected function previewOtherRefund(OrderModel $order)
    {
        $serviceFee = $this->getServiceFee($order);
        $serviceRate = $this->getServiceRate($order);

        $refundAmount = round($order->amount - $serviceFee, 2);

        return [
            'item_type' => $order->item_type,
            'item_info' => $order->item_info,
            'refund_amount' => $refundAmount,
            'service_fee' => $serviceFee,
            'service_rate' => $serviceRate,
        ];
    }

    protected function getServiceFee(OrderModel $order)
    {
        $serviceRate = $this->getServiceRate($order);

        $serviceFee = round($order->amount * $serviceRate / 100, 2);

        return $serviceFee >= 0.01 ? $serviceFee : 0.00;
    }

    protected function getServiceRate(OrderModel $order)
    {
        $orderRepo = new OrderRepo();

        $trade = $orderRepo->findLastTrade($order->id);

        $alipay = $this->getSettings('pay.alipay');
        $wxpay = $this->getSettings('pay.wxpay');

        $serviceRate = 5;

        switch ($trade->channel) {
            case TradeModel::CHANNEL_ALIPAY:
                $serviceRate = $alipay['service_rate'] ?? $serviceRate;
                break;
            case TradeModel::CHANNEL_WXPAY:
                $serviceRate = $wxpay['service_rate'] ?? $serviceRate;
                break;
        }

        return $serviceRate;
    }

    protected function getCourseRefundRate($courseId, $userId)
    {
        $courseRepo = new CourseRepo();

        $courseLessons = $courseRepo->findLessons($courseId);

        if ($courseLessons->count() == 0) return 1.00;

        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->findCourseUser($courseId, $userId);

        if (!$courseUser) return 1.00;

        $userLearnings = $courseRepo->findUserLearnings($courseId, $userId, $courseUser->plan_id);

        if ($userLearnings->count() == 0) return 1.00;

        $consumedUserLearnings = $userLearnings->filter(function ($item) {
            if ($item->consumed == 1) return $item;
        });

        if (count($consumedUserLearnings) == 0) return 1.00;

        $courseLessonIds = kg_array_column($courseLessons->toArray(), 'id');
        $consumedUserLessonIds = kg_array_column($consumedUserLearnings, 'chapter_id');
        $consumedLessonIds = array_intersect($courseLessonIds, $consumedUserLessonIds);

        $totalCount = count($courseLessonIds);
        $consumedCount = count($consumedLessonIds);
        $refundCount = $totalCount - $consumedCount;

        return round($refundCount / $totalCount, 4);
    }

}
