<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Order;

use App\Models\Course as CourseModel;
use App\Models\KgProduct as KgProductModel;
use App\Models\Order as OrderModel;
use App\Models\User as UserModel;
use App\Models\Vip as VipModel;
use App\Repos\Order as OrderRepo;
use App\Services\Logic\Service as LogicService;
use App\Traits\Client as ClientTrait;
use App\Validators\Order as OrderValidator;
use App\Validators\UserLimit as UserLimitValidator;

class OrderCreate extends LogicService
{

    /**
     * @var float 订单金额
     */
    protected float $amount = 0.00;

    use ClientTrait;

    public function handle(): ?OrderModel
    {
        $itemId = $this->request->getPost('item_id', ['trim', 'int']);
        $itemType = $this->request->getPost('item_type', ['trim', 'int']);

        $user = $this->getLoginUser();

        $orderRepo = new OrderRepo();

        $order = $orderRepo->findUserLastPendingOrder($user->id, $itemId, $itemType);

        if ($order) return $order;

        $this->checkUserDailyOrderLimit($user);

        $orderValidator = new OrderValidator();

        $orderValidator->checkItemType($itemType);

        $order = null;

        if ($itemType == KgProductModel::ITEM_COURSE) {

            $course = $orderValidator->checkCourse($itemId);

            $orderValidator->checkIfBoughtCourse($user->id, $course->id);

            $calculator = new CoursePayCalculator($course);

            $calculator->handleNormalPay($user);

            $this->amount = $calculator->payAmount;

            $orderValidator->checkAmount($this->amount);

            $order = $this->createCourseOrder($course, $user);

        } elseif ($itemType == KgProductModel::ITEM_VIP) {

            $vip = $orderValidator->checkVip($itemId);

            $calculator = new VipPayCalculator($vip);

            $calculator->handleNormalPay($user);

            $this->amount = $calculator->payAmount;

            $orderValidator->checkAmount($this->amount);

            $order = $this->createVipOrder($vip, $user);
        }

        $this->incrUserDailyOrderCount($user);

        return $order;
    }

    protected function createCourseOrder(CourseModel $course, UserModel $user): OrderModel
    {
        $itemInfo = [];

        $itemInfo['course'] = $this->handleCourseInfo($course);

        $order = new OrderModel();

        $order->owner_id = $user->id;
        $order->item_id = $course->id;
        $order->item_type = KgProductModel::ITEM_COURSE;
        $order->item_info = $itemInfo;
        $order->client_type = $this->getClientType();
        $order->client_ip = $this->getClientIp();
        $order->subject = "课程 - {$course->title}";
        $order->amount = $this->amount;

        $order->create();

        return $order;
    }

    protected function createVipOrder(VipModel $vip, UserModel $user): OrderModel
    {
        $itemInfo = [];

        $itemInfo['vip'] = $this->handleVipInfo($vip, $user);

        $order = new OrderModel();

        $order->owner_id = $user->id;
        $order->item_id = $vip->id;
        $order->item_type = KgProductModel::ITEM_VIP;
        $order->item_info = $itemInfo;
        $order->client_type = $this->getClientType();
        $order->client_ip = $this->getClientIp();
        $order->subject = "会员 - 会员服务（{$vip->title}）";
        $order->amount = $this->amount;

        $order->create();

        return $order;
    }

    protected function handleCourseInfo(CourseModel $course): array
    {
        $studyExpiryTime = strtotime("+{$course->study_expiry} months");
        $refundExpiryTime = strtotime("+{$course->refund_expiry} days");

        $cover = CourseModel::getCoverPath($course->cover);

        return [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $cover,
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

    protected function handleVipInfo(VipModel $vip, UserModel $user): array
    {
        $baseTime = $user->vip_expiry_time > time() ? $user->vip_expiry_time : time();
        $expiryTime = strtotime("+{$vip->expiry} months", $baseTime);

        $cover = VipModel::getCoverPath($vip->cover);

        return [
            'id' => $vip->id,
            'title' => $vip->title,
            'cover' => $cover,
            'price' => $vip->price,
            'expiry' => $vip->expiry,
            'expiry_time' => $expiryTime,
        ];
    }

    protected function incrUserDailyOrderCount(UserModel $user): void
    {
        $this->eventsManager->fire('UserDailyCounter:incrOrderCount', $this, $user);
    }

    protected function checkUserDailyOrderLimit(UserModel $user): void
    {
        $validator = new UserLimitValidator();

        $validator->checkDailyOrderLimit($user);
    }

}
