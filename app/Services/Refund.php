<?php

namespace App\Services;

use App\Models\Order as OrderModel;
use App\Repos\Course as CourseRepo;

class Refund extends Service
{

    public function getRefundAmount(OrderModel $order)
    {
        $amount = 0.00;

        if ($order->status != OrderModel::STATUS_FINISHED) {
            return $amount;
        }

        if ($order->item_type == OrderModel::TYPE_COURSE) {
            $amount = $this->getCourseRefundAmount($order);
        } elseif ($order->item_type == OrderModel::TYPE_PACKAGE) {
            $amount = $this->getPackageRefundAmount($order);
        }

        return $amount;
    }

    protected function getCourseRefundAmount(OrderModel $order)
    {
        $course = $order->item_info['course'];

        $courseId = $order->item_id;
        $userId = $order->user_id;
        $amount = $order->amount;
        $expireTime = $course['expire_time'];

        $refundAmount = 0.00;

        if ($expireTime > time()) {
            $percent = $this->getCourseRefundPercent($courseId, $userId);
            $refundAmount = $amount * $percent;
        }

        return $refundAmount;
    }

    protected function getPackageRefundAmount(OrderModel $order)
    {
        $courses = $order->item_info['courses'];
        $userId = $order->user_id;
        $amount = $order->amount;

        $totalMarketPrice = 0.00;

        foreach ($courses as $course) {
            $totalMarketPrice += $course['market_price'];
        }

        $totalRefundAmount = 0.00;

        /**
         * 按照占比方式计算退款
         */
        foreach ($courses as $course) {
            if ($course['expire_time'] > time()) {
                $pricePercent = round($course['market_price'] / $totalMarketPrice, 4);
                $refundPercent = $this->getCourseRefundPercent($userId, $course['id']);
                $refundAmount = round($amount * $pricePercent * $refundPercent, 2);
                $totalRefundAmount += $refundAmount;
            }
        }

        return $totalRefundAmount;
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

        $percent = round($refundCount / $totalCount, 4);

        return $percent;
    }

}
