<?php

namespace App\Services\Frontend\Order;

use App\Models\Course as CourseModel;
use App\Models\Order as OrderModel;
use App\Models\Package as PackageModel;
use App\Models\Reward as RewardModel;
use App\Models\User as UserModel;
use App\Models\Vip as VipModel;
use App\Repos\Order as OrderRepo;
use App\Repos\Package as PackageRepo;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\Order as OrderValidator;
use App\Validators\UserDailyLimit as UserDailyLimitValidator;

class OrderCreate extends FrontendService
{

    public function handle()
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
        if ($order) return $order;

        if ($post['item_type'] == OrderModel::ITEM_COURSE) {

            $course = $validator->checkCourse($post['item_id']);

            $validator->checkIfBoughtCourse($user->id, $course->id);

            $order = $this->createCourseOrder($course, $user);

        } elseif ($post['item_type'] == OrderModel::ITEM_PACKAGE) {

            $package = $validator->checkPackage($post['item_id']);

            $validator->checkIfBoughtPackage($user->id, $package->id);

            $order = $this->createPackageOrder($package, $user);

        } elseif ($post['item_type'] == OrderModel::ITEM_VIP) {

            $vip = $validator->checkVip($post['item_id']);

            $order = $this->createVipOrder($vip, $user);

        } elseif ($post['item_type'] == OrderModel::ITEM_REWARD) {

            list($courseId, $rewardId) = explode('-', $post['item_id']);

            $course = $validator->checkCourse($courseId);
            $reward = $validator->checkReward($rewardId);

            $order = $this->createRewardOrder($course, $reward, $user);
        }

        $this->incrUserDailyOrderCount($user);

        return $order;
    }

    protected function createCourseOrder(CourseModel $course, UserModel $user)
    {
        $itemInfo = [];

        $itemInfo['course'] = $this->handleCourseInfo($course);

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

    protected function createPackageOrder(PackageModel $package, UserModel $user)
    {
        $packageRepo = new PackageRepo();

        $courses = $packageRepo->findCourses($package->id);

        $itemInfo = [];

        $itemInfo['package'] = [
            'id' => $package->id,
            'title' => $package->title,
            'market_price' => $package->market_price,
            'vip_price' => $package->vip_price,
        ];

        foreach ($courses as $course) {
            $itemInfo['courses'][] = $this->handleCourseInfo($course);
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

    protected function createVipOrder(VipModel $vip, UserModel $user)
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

    protected function createRewardOrder(CourseModel $course, RewardModel $reward, UserModel $user)
    {
        $itemInfo = [
            'course' => $this->handleCourseInfo($course),
            'reward' => [
                'id' => $reward->id,
                'title' => $reward->title,
                'price' => $reward->price,
            ]
        ];

        $order = new OrderModel();

        $order->user_id = $user->id;
        $order->item_id = "{$course->id}-{$reward->id}";
        $order->item_type = OrderModel::ITEM_REWARD;
        $order->item_info = $itemInfo;
        $order->amount = $reward->price;
        $order->subject = "赞赏 - {$course->title}";

        $order->create();

        return $order;
    }

    protected function handleCourseInfo(CourseModel $course)
    {
        $studyExpiryTime = strtotime("+{$course->study_expiry} months");
        $refundExpiryTime = strtotime("+{$course->refund_expiry} days");

        $course->cover = CourseModel::getCoverPath($course->cover);

        return [
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

    protected function incrUserDailyOrderCount(UserModel $user)
    {
        $this->eventsManager->fire('userDailyCounter:incrOrderCount', $this, $user);
    }

}
