<?php

namespace App\Services\Frontend;

use App\Models\Course as CourseModel;
use App\Models\Order as OrderModel;
use App\Models\Package as PackageModel;
use App\Models\User as UserModel;
use App\Models\Vip as VipModel;
use App\Repos\Order as OrderRepo;
use App\Repos\Package as PackageRepo;
use App\Validators\Order as OrderValidator;
use App\Validators\UserDailyLimit as UserDailyLimitValidator;

class OrderCreate extends Service
{

    /**
     * @return OrderModel
     */
    public function createOrder()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $validator = new UserDailyLimitValidator();

        $validator->checkOrderLimit($user);

        $validator = new OrderValidator();

        $validator->checkItemType($post['item_type']);

        $orderRepo = new OrderRepo();

        $order = $orderRepo->findUserLastPendingOrder($user->id, $post['item_id'], $post['item_type']);

        /**
         * 存在新鲜的未支付订单直接返回（减少订单记录）
         */
        if ($order) {

            $caseA = $order->status == OrderModel::STATUS_PENDING;
            $caseB = time() - $order->create_time < 6 * 3600;

            if ($caseA && $caseB) {
                return $order;
            }
        }

        if ($post['item_type'] == OrderModel::ITEM_COURSE) {

            $course = $validator->checkItemCourse($post['item_id']);

            $validator->checkIfBoughtCourse($user->id, $course->id);

            $order = $this->createCourseOrder($course, $user);

        } elseif ($post['item_type'] == OrderModel::ITEM_PACKAGE) {

            $package = $validator->checkItemPackage($post['item_id']);

            $validator->checkIfBoughtPackage($user->id, $package->id);

            $order = $this->createPackageOrder($package, $user);

        } elseif ($post['item_type'] == OrderModel::ITEM_VIP) {

            $vip = $validator->checkItemVip($post['item_id']);

            $order = $this->createVipOrder($vip, $user);
        }

        $this->incrUserDailyOrderCount($user);

        return $order;
    }

    /**
     * @param CourseModel $course
     * @param UserModel $user
     * @return OrderModel $order
     */
    public function createCourseOrder(CourseModel $course, UserModel $user)
    {
        $studyExpiryTime = strtotime("+{$course->study_expiry} months");
        $refundExpiryTime = strtotime("+{$course->refund_expiry} days");

        $itemInfo = [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'cover' => $course->cover,
                'market_price' => $course->market_price,
                'vip_price' => $course->vip_price,
                'study_expiry' => $course->study_expiry,
                'refund_expiry' => $course->refund_expiry,
                'study_expiry_time' => $studyExpiryTime,
                'refund_expiry_time' => $refundExpiryTime,
            ]
        ];

        $amount = $user->vip ? $course->vip_price : $course->market_price;

        $order = new OrderModel();

        $order->user_id = $user->id;
        $order->item_id = $course->id;
        $order->item_type = OrderModel::ITEM_COURSE;
        $order->item_info = $itemInfo;
        $order->amount = $amount;
        $order->subject = "课程 - {$course->title}";

        $order->create();

        return $order;
    }

    /**
     * @param PackageModel $package
     * @param UserModel $user
     * @return OrderModel $order
     */
    public function createPackageOrder(PackageModel $package, UserModel $user)
    {
        $packageRepo = new PackageRepo();

        /**
         * @var CourseModel[] $courses
         */
        $courses = $packageRepo->findCourses($package->id);

        $itemInfo = [];

        $itemInfo['package'] = [
            'id' => $package->id,
            'title' => $package->title,
            'market_price' => $package->market_price,
            'vip_price' => $package->vip_price,
        ];

        foreach ($courses as $course) {

            $studyExpiryTime = strtotime("+{$course->study_expiry} months");
            $refundExpiryTime = strtotime("+{$course->refund_expiry} days");

            $itemInfo['courses'][] = [
                'id' => $course->id,
                'title' => $course->title,
                'cover' => $course->cover,
                'market_price' => $course->market_price,
                'vip_price' => $course->vip_price,
                'study_expiry' => $course->study_expiry,
                'refund_expiry' => $course->refund_expiry,
                'study_expiry_time' => $studyExpiryTime,
                'refund_expiry_time' => $refundExpiryTime,
            ];
        }

        $amount = $user->vip ? $package->vip_price : $package->market_price;

        $order = new OrderModel();

        $order->user_id = $user->id;
        $order->item_id = $package->id;
        $order->item_type = OrderModel::ITEM_PACKAGE;
        $order->item_info = $itemInfo;
        $order->amount = $amount;
        $order->subject = "套餐 - {$package->title}";

        $order->create();

        return $order;
    }

    /**
     * @param VipModel $vip
     * @param UserModel $user
     * @return OrderModel
     */
    public function createVipOrder(VipModel $vip, UserModel $user)
    {
        $baseTime = $user->vip_expiry_time > time() ? $user->vip_expiry_time : time();
        $expiryTime = strtotime("+{$vip->expiry} months", $baseTime);

        $itemInfo = [
            'vip' => [
                'id' => $vip->id,
                'title' => $vip->title,
                'price' => $vip->price,
                'expiry' => $vip->expiry,
                'expiry_time' => $expiryTime,
            ]
        ];

        $order = new OrderModel();

        $order->user_id = $user->id;
        $order->item_id = $vip->id;
        $order->item_type = OrderModel::ITEM_VIP;
        $order->item_info = $itemInfo;
        $order->amount = $vip->price;
        $order->subject = "会员 - 会员服务（{$vip->title}）";

        $order->create();

        return $order;
    }

    /**
     * @param UserModel $user
     */
    protected function incrUserDailyOrderCount(UserModel $user)
    {
        $this->eventsManager->fire('userDailyCounter:incrOrderCount', $this, $user);
    }

}
