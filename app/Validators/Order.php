<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Order as OrderModel;
use App\Repos\Course as CourseRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\Package as PackageRepo;
use App\Repos\Reward as RewardRepo;
use App\Repos\Vip as VipRepo;

class Order extends Validator
{

    public function checkOrder($id)
    {
        return $this->checkOrderById($id);
    }

    public function checkOrderById($id)
    {
        $orderRepo = new OrderRepo();

        $order = $orderRepo->findById($id);

        if (!$order) {
            throw new BadRequestException('order.not_found');
        }

        return $order;
    }

    public function checkOrderBySn($sn)
    {
        $orderRepo = new OrderRepo();

        $order = $orderRepo->findBySn($sn);

        if (!$order) {
            throw new BadRequestException('order.not_found');
        }

        return $order;
    }

    public function checkItemType($itemType)
    {
        $list = OrderModel::itemTypes();

        if (!isset($list[$itemType])) {
            throw new BadRequestException('order.invalid_item_type');
        }

        return $itemType;
    }

    public function checkCourse($itemId)
    {
        $courseRepo = new CourseRepo();

        $item = $courseRepo->findById($itemId);

        if (!$item) {
            throw new BadRequestException('order.item_not_found');
        }

        return $item;
    }

    public function checkPackage($itemId)
    {
        $packageRepo = new PackageRepo();

        $item = $packageRepo->findById($itemId);

        if (!$item) {
            throw new BadRequestException('order.item_not_found');
        }

        return $item;
    }

    public function checkVip($itemId)
    {
        $vipRepo = new VipRepo();

        $item = $vipRepo->findById($itemId);

        if (!$item) {
            throw new BadRequestException('order.item_not_found');
        }

        return $item;
    }

    public function checkReward($itemId)
    {
        $rewardRepo = new RewardRepo();

        $item = $rewardRepo->findById($itemId);

        if (!$item) {
            throw new BadRequestException('order.item_not_found');
        }

        return $item;
    }

    public function checkAmount($amount)
    {
        $value = $this->filter->sanitize($amount, ['trim', 'float']);

        if ($value < 0.01 || $value > 10000) {
            throw new BadRequestException('order.invalid_pay_amount');
        }

        return $value;
    }

    public function checkIfAllowCancel($order)
    {
        if ($order->status != OrderModel::STATUS_PENDING) {
            throw new BadRequestException('order.cancel_not_allowed');
        }
    }

    public function checkIfBoughtCourse($userId, $courseId)
    {
        $orderRepo = new OrderRepo();

        $itemType = OrderModel::ITEM_PACKAGE;

        $order = $orderRepo->findUserLastFinishedOrder($userId, $courseId, $itemType);

        if ($order) {

            /**
             * @var array $itemInfo
             */
            $itemInfo = $order->item_info;

            if ($itemInfo['course']['expiry_time'] > time()) {
                throw new BadRequestException('order.has_bought_course');
            }
        }
    }

    public function checkIfBoughtPackage($userId, $packageId)
    {
        $orderRepo = new OrderRepo();

        $itemType = OrderModel::ITEM_PACKAGE;

        $order = $orderRepo->findUserLastFinishedOrder($userId, $packageId, $itemType);

        if ($order) {
            throw new BadRequestException('order.has_bought_package');
        }
    }

}
