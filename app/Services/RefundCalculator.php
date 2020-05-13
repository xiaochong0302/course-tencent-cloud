<?php

namespace App\Services;

use App\Models\Order as OrderModel;
use App\Repos\Course as CourseRepo;

class RefundCalculator extends Service
{

    public function handle(OrderModel $order)
    {
        $result = [];

        switch ($order->item_type) {
            case OrderModel::ITEM_COURSE:
                $result = $this->handleCourseRefund($order);
                break;
            case OrderModel::ITEM_PACKAGE:
                $result = $this->handlePackageRefund($order);
                break;
        }

        return $result;
    }

    protected function handleCourseRefund(OrderModel $order)
    {
        /**
         * @var array $itemInfo
         */
        $itemInfo = $order->item_info;

        $refundPercent = 0.00;
        $refundAmount = 0.00;

        if ($itemInfo['course']['refund_expiry_time'] > time()) {
            $refundPercent = $this->getCourseRefundPercent($order->item_id, $order->user_id);
            $refundAmount = $order->amount * $refundPercent;
        }

        $itemInfo['course']['refund_percent'] = $refundPercent;
        $itemInfo['course']['refund_amount'] = $refundAmount;

        return [
            'item_type' => $order->item_type,
            'item_info' => $itemInfo,
            'refund_amount' => $refundAmount,
        ];
    }

    protected function handlePackageRefund(OrderModel $order)
    {
        /**
         * @var array $itemInfo
         */
        $itemInfo = $order->item_info;

        $totalMarketPrice = 0.00;

        foreach ($itemInfo['courses'] as $course) {
            $totalMarketPrice += $course['market_price'];
        }

        $totalRefundAmount = 0.00;

        /**
         * 按照占比方式计算退款
         */
        foreach ($itemInfo['courses'] as &$course) {

            $refundPercent = 0.00;
            $refundAmount = 0.00;

            if ($course['refund_expiry_time'] > time()) {
                $pricePercent = round($course['market_price'] / $totalMarketPrice, 4);
                $refundPercent = $this->getCourseRefundPercent($order->user_id, $course['id']);
                $refundAmount = round($order->amount * $pricePercent * $refundPercent, 2);
                $totalRefundAmount += $refundAmount;
            }

            $course['item_info']['refund_percent'] = $refundPercent;
            $course['item_info']['refund_amount'] = $refundAmount;
        }

        return [
            'item_type' => $order->item_type,
            'item_info' => $itemInfo,
            'refund_amount' => $totalRefundAmount,
        ];
    }

    protected function getCourseRefundPercent($courseId, $userId)
    {
        $courseRepo = new CourseRepo();

        $courseLessons = $courseRepo->findLessons($courseId);

        if ($courseLessons->count() == 0) {
            return 1.00;
        }

        $userLearnings = $courseRepo->findConsumedUserLearnings($courseId, $userId);

        if ($userLearnings->count() == 0) {
            return 1.00;
        }

        $courseLessonIds = kg_array_column($courseLessons->toArray(), 'id');
        $userLessonIds = kg_array_column($userLearnings->toArray(), 'chapter_id');
        $consumedLessonIds = array_intersect($courseLessonIds, $userLessonIds);

        $totalCount = count($courseLessonIds);
        $consumedCount = count($consumedLessonIds);
        $refundCount = $totalCount - $consumedCount;

        return round($refundCount / $totalCount, 4);
    }

}
