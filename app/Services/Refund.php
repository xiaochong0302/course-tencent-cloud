<?php

namespace App\Services;

use App\Models\Order as OrderModel;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseUser as CourseUserRepo;

class Refund extends Service
{

    public function preview(OrderModel $order)
    {
        $result = [];

        switch ($order->item_type) {
            case OrderModel::ITEM_COURSE:
                $result = $this->previewCourseRefund($order);
                break;
            case OrderModel::ITEM_PACKAGE:
                $result = $this->previewPackageRefund($order);
                break;
        }

        return $result;
    }

    protected function previewCourseRefund(OrderModel $order)
    {
        /**
         * @var array $itemInfo
         */
        $itemInfo = $order->item_info;

        $itemInfo['course']['cover'] = kg_ss_cover_url($itemInfo['course']['cover']);

        $refundPercent = 0.00;
        $refundAmount = 0.00;

        if ($itemInfo['course']['refund_expiry_time'] > time()) {
            $refundPercent = $this->getCourseRefundPercent($order->item_id, $order->owner_id);
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

    protected function previewPackageRefund(OrderModel $order)
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

            $course['cover'] = kg_ss_cover_url($course['cover']);

            $refundPercent = 0.00;
            $refundAmount = 0.00;

            if ($course['refund_expiry_time'] > time()) {
                $pricePercent = round($course['market_price'] / $totalMarketPrice, 4);
                $refundPercent = $this->getCourseRefundPercent($course['id'], $order->owner_id);
                $refundAmount = round($order->amount * $pricePercent * $refundPercent, 2);
                $totalRefundAmount += $refundAmount;
            }

            $course['refund_percent'] = $refundPercent;
            $course['refund_amount'] = $refundAmount;
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

        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->findCourseUser($courseId, $userId);

        if (!$courseUser) {
            return 1.00;
        }

        $userLearnings = $courseRepo->findUserLearnings($courseId, $userId, $courseUser->plan_id);

        if ($userLearnings->count() == 0) {
            return 1.00;
        }

        /**
         * @var array $consumedUserLearnings
         */
        $consumedUserLearnings = $userLearnings->filter(function ($item) {
            if ($item->consumed == 1) {
                return $item;
            }
        });

        if (count($consumedUserLearnings) == 0) {
            return 1.00;
        }

        $courseLessonIds = kg_array_column($courseLessons->toArray(), 'id');
        $consumedUserLessonIds = kg_array_column($consumedUserLearnings, 'chapter_id');
        $consumedLessonIds = array_intersect($courseLessonIds, $consumedUserLessonIds);

        $totalCount = count($courseLessonIds);
        $consumedCount = count($consumedLessonIds);
        $refundCount = $totalCount - $consumedCount;

        return round($refundCount / $totalCount, 4);
    }

}
