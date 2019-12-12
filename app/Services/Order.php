<?php

namespace App\Services;

use App\Models\Course as CourseModel;
use App\Models\Order as OrderModel;
use App\Models\Package as PackageModel;
use App\Repos\Package as PackageRepo;

class Order extends Service
{

    /**
     * 创建课程订单
     *
     * @param CourseModel $course
     * @return OrderModel $order
     */
    public function createCourseOrder(CourseModel $course)
    {
        $authUser = $this->getDI()->get('auth')->getAuthUser();

        $expiry = $course->expiry;
        $expireTime = strtotime("+{$expiry} days");

        $itemInfo = [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'cover' => $course->cover,
                'expiry' => $course->expiry,
                'market_price' => $course->market_price,
                'vip_price' => $course->vip_price,
                'expire_time' => $expireTime,
            ]
        ];

        $amount = $authUser->vip ? $course->vip_price : $course->market_price;

        $order = new OrderModel();

        $order->user_id = $authUser->id;
        $order->item_id = $course->id;
        $order->item_type = OrderModel::TYPE_COURSE;
        $order->item_info = $itemInfo;
        $order->amount = $amount;
        $order->subject = "课程 - {$course->title}";
        $order->create();

        return $order;
    }

    /**
     * 创建套餐订单
     *
     * @param PackageModel $package
     * @return OrderModel $order
     */
    public function createPackageOrder(PackageModel $package)
    {
        $authUser = $this->getDI()->get('auth')->getAuthUser();

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
            $expiry = $course->expiry;
            $expireTime = strtotime("+{$expiry} days");
            $itemInfo['courses'][] = [
                'id' => $course->id,
                'title' => $course->title,
                'cover' => $course->cover,
                'expiry' => $expiry,
                'market_price' => $course->market_price,
                'vip_price' => $course->vip_price,
                'expire_time' => $expireTime,
            ];
        }

        $amount = $authUser->vip ? $package->vip_price : $package->market_price;

        $order = new OrderModel();

        $order->user_id = $authUser->id;
        $order->item_id = $package->id;
        $order->item_type = OrderModel::TYPE_PACKAGE;
        $order->item_info = $itemInfo;
        $order->amount = $amount;
        $order->subject = "套餐 - {$package->title}";
        $order->create();

        return $order;
    }

    /**
     * 创建赞赏订单
     *
     * @param CourseModel $course
     * @param float $amount
     * @return OrderModel $order
     */
    public function createRewardOrder(CourseModel $course, $amount)
    {
        $authUser = $this->getDI()->get('auth')->getAuthUser();

        $itemInfo = [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'cover' => $course->cover,
            ]
        ];

        $order = new OrderModel();

        $order->user_id = $authUser->id;
        $order->item_id = $course->id;
        $order->item_type = OrderModel::TYPE_REWARD;
        $order->item_info = $itemInfo;
        $order->amount = $amount;
        $order->subject = "赞赏 - {$course->title}";
        $order->create();

        return $order;
    }

    /**
     * 创建会员服务订单
     *
     * @param string $duration
     * @return OrderModel
     */
    public function createVipOrder($duration)
    {
        $authUser = $this->getDI()->get('auth')->getAuthUser();

        $vipInfo = new VipInfo();

        $vipItem = $vipInfo->getItem($duration);

        $itemInfo = [
            'vip' => [
                'duration' => $vipItem['duration'],
                'label' => $vipItem['label'],
                'price' => $vipItem['price'],
            ]
        ];

        $order = new OrderModel();

        $order->user_id = $authUser->id;
        $order->item_type = OrderModel::TYPE_VIP;
        $order->item_info = $itemInfo;
        $order->amount = $vipItem['price'];
        $order->subject = "会员 - 会员服务（{$vipItem['label']}）";
        $order->create();

        return $order;
    }

    /**
     * 获取订单来源
     */
    protected function getSource()
    {

    }

}
