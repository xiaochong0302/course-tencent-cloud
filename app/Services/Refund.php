<?php

namespace App\Services;

use App\Models\Course as CourseModel;
use App\Models\Order as OrderModel;
use App\Repos\Course as CourseRepo;

class Refund extends Service
{

    public function getRefundAmount(OrderModel $order)
    {
        $amount = 0.00;

        if ($order->status != OrderModel::STATUS_FINISHED) {
            //return $amount;
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
        $course = $order->item_info->course;

        $courseId = $order->item_id;
        $userId = $order->user_id;
        $amount = $order->amount;
        $expireTime = $course->expire_time;

        $refundAmount = 0.00;

        if ($expireTime > time()) {
            $percent = $this->getCourseRefundPercent($courseId, $userId);
            $refundAmount = $amount * $percent;
        }

        return $refundAmount;
    }

    protected function getPackageRefundAmount(OrderModel $order)
    {
        $userId = $order->user_id;
        $courses = $order->item_info->courses;
        $amount = $order->amount;

        $totalMarketPrice = 0.00;

        foreach ($courses as $course) {
            $totalMarketPrice += $course->market_price;
        }

        $totalRefundAmount = 0.00;

        /**
         * 按照占比方式计算退款
         */
        foreach ($courses as $course) {
            if ($course->expire_time > time()) {
                $pricePercent = round($course->market_price / $totalMarketPrice, 4);
                $refundPercent = $this->getCourseRefundPercent($course->id, $userId);
                $refundAmount = round($amount * $pricePercent * $refundPercent, 2);
                $totalRefundAmount += $refundAmount;
            }
        }

        return $totalRefundAmount;
    }

    protected function getCourseRefundPercent($courseId, $userId)
    {
        $courseRepo = new CourseRepo();

        $userLessons = $courseRepo->findUserLessons($courseId, $userId);

        if ($userLessons->count() == 0) {
            return 1.00;
        }

        $course = $courseRepo->findById($courseId);
        $lessons = $courseRepo->findLessons($courseId);

        $durationMapping = [];

        foreach ($lessons as $lesson) {
            $durationMapping[$lesson->id] = $lesson->attrs->duration ?? null;
        }

        $totalCount = $course->lesson_count;
        $finishCount = 0;

        /**
         * 消费规则
         * 1.点播观看时间大于时长30%
         * 2.直播观看时间超过10分钟
         * 3.图文浏览即消费
         */
        foreach ($userLessons as $learning) {
            $chapterId = $learning->chapter_id;
            $duration = $durationMapping[$chapterId] ?? null;
            if ($course->model == CourseModel::MODEL_VOD) {
                if ($duration && $learning->duration > 0.3 * $duration) {
                    $finishCount++;
                }
            } elseif ($course->model == CourseModel::MODEL_LIVE) {
                if ($learning->duration > 600) {
                    $finishCount++;
                }
            } elseif ($course->model == CourseModel::MODEL_LIVE) {
                $finishCount++;
            }
        }

        $refundCount = $totalCount - $finishCount;

        $percent = round($refundCount / $totalCount, 4);

        return $percent;
    }

}
