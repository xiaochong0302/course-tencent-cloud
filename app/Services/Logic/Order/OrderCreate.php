<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Order;

use App\Models\Course as CourseModel;
use App\Models\Order as OrderModel;
use App\Models\Package as PackageModel;
use App\Models\Reward as RewardModel;
use App\Models\User as UserModel;
use App\Models\Vip as VipModel;
use App\Repos\Order as OrderRepo;
use App\Repos\Package as PackageRepo;
use App\Services\Logic\Service as LogicService;
use App\Traits\Client as ClientTrait;
use App\Validators\Order as OrderValidator;
use App\Validators\UserLimit as UserLimitValidator;

class OrderCreate extends LogicService
{

    /**
     * @var float 订单金额
     */
    protected $amount = 0.00;

    /**
     * @var int 促销编号
     */
    protected $promotion_id = 0;

    /**
     * @var int 促销类型
     */
    protected $promotion_type = 0;

    /**
     * @var array 促销信息
     */
    protected $promotion_info = [];

    use ClientTrait;

    public function handle()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $validator = new UserLimitValidator();

        $validator->checkDailyOrderLimit($user);

        $orderValidator = new OrderValidator();

        $orderValidator->checkItemType($post['item_type']);

        $orderRepo = new OrderRepo();

        $order = $orderRepo->findUserLastPendingOrder($user->id, $post['item_id'], $post['item_type']);

        /**
         * 存在新鲜的未支付订单直接返回（减少订单记录）
         */
        if ($order) return $order;

        if ($post['item_type'] == OrderModel::ITEM_COURSE) {

            $course = $orderValidator->checkCourse($post['item_id']);

            $orderValidator->checkIfBoughtCourse($user->id, $course->id);

            $this->amount = $user->vip ? $course->vip_price : $course->market_price;

            $orderValidator->checkAmount($this->amount);

            $order = $this->createCourseOrder($course, $user);

        } elseif ($post['item_type'] == OrderModel::ITEM_PACKAGE) {

            $package = $orderValidator->checkPackage($post['item_id']);

            $orderValidator->checkIfBoughtPackage($user->id, $package->id);

            $this->amount = $user->vip ? $package->vip_price : $package->market_price;

            $orderValidator->checkAmount($this->amount);

            $order = $this->createPackageOrder($package, $user);

        } elseif ($post['item_type'] == OrderModel::ITEM_REWARD) {

            list($courseId, $rewardId) = explode('-', $post['item_id']);

            $course = $orderValidator->checkCourse($courseId);
            $reward = $orderValidator->checkReward($rewardId);

            $this->amount = $reward->price;

            $orderValidator->checkAmount($this->amount);

            $order = $this->createRewardOrder($course, $reward, $user);

        } elseif ($post['item_type'] == OrderModel::ITEM_VIP) {

            $vip = $orderValidator->checkVip($post['item_id']);

            $this->amount = $vip->price;

            $orderValidator->checkAmount($this->amount);

            $order = $this->createVipOrder($vip, $user);
        }

        $this->incrUserDailyOrderCount($user);

        return $order;
    }

    protected function createCourseOrder(CourseModel $course, UserModel $user)
    {
        $itemInfo = [];

        $itemInfo['course'] = $this->handleCourseInfo($course);

        $order = new OrderModel();

        $order->owner_id = $user->id;
        $order->item_id = $course->id;
        $order->item_type = OrderModel::ITEM_COURSE;
        $order->item_info = $itemInfo;
        $order->client_type = $this->getClientType();
        $order->client_ip = $this->getClientIp();
        $order->subject = "课程 - {$course->title}";
        $order->amount = $this->amount;
        $order->promotion_id = $this->promotion_id;
        $order->promotion_type = $this->promotion_type;
        $order->promotion_info = $this->promotion_info;

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

        $order = new OrderModel();

        $order->owner_id = $user->id;
        $order->item_id = $package->id;
        $order->item_type = OrderModel::ITEM_PACKAGE;
        $order->item_info = $itemInfo;
        $order->client_type = $this->getClientType();
        $order->client_ip = $this->getClientIp();
        $order->subject = "套餐 - {$package->title}";
        $order->amount = $this->amount;
        $order->promotion_id = $this->promotion_id;
        $order->promotion_type = $this->promotion_type;
        $order->promotion_info = $this->promotion_info;

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

        $order->owner_id = $user->id;
        $order->item_id = $vip->id;
        $order->item_type = OrderModel::ITEM_VIP;
        $order->item_info = $itemInfo;
        $order->client_type = $this->getClientType();
        $order->client_ip = $this->getClientIp();
        $order->subject = "会员 - 会员服务（{$vip->title}）";
        $order->amount = $this->amount;
        $order->promotion_id = $this->promotion_id;
        $order->promotion_type = $this->promotion_type;
        $order->promotion_info = $this->promotion_info;

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

        $order->owner_id = $user->id;
        $order->item_id = $course->id;
        $order->item_type = OrderModel::ITEM_REWARD;
        $order->item_info = $itemInfo;
        $order->client_type = $this->getClientType();
        $order->client_ip = $this->getClientIp();
        $order->subject = "赞赏 - {$course->title}";
        $order->amount = $this->amount;
        $order->promotion_id = $this->promotion_id;
        $order->promotion_type = $this->promotion_type;
        $order->promotion_info = $this->promotion_info;

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
            'model' => $course->model,
            'attrs' => $course->attrs,
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
        $this->eventsManager->fire('UserDailyCounter:incrOrderCount', $this, $user);
    }

}
