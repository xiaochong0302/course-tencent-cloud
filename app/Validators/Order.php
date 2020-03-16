<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Order as OrderModel;
use App\Repos\Course as CourseRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\Package as PackageRepo;
use App\Repos\Vip as VipRepo;

class Order extends Validator
{

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

    public function checkItem($itemId, $itemType)
    {
        if ($itemType == OrderModel::ITEM_COURSE) {
            $courseRepo = new CourseRepo();
            $item = $courseRepo->findById($itemId);
            if (!$item) {
                throw new BadRequestException('order.item_not_found');
            }
        } elseif ($itemType == OrderModel::ITEM_PACKAGE) {
            $packageRepo = new PackageRepo();
            $item = $packageRepo->findById($itemId);
            if (!$item) {
                throw new BadRequestException('order.item_not_found');
            }
        } elseif ($itemType == OrderModel::ITEM_VIP) {
            $vipRepo = new VipRepo();
            $item = $vipRepo->findById($itemId);
            if (!$item) {
                throw new BadRequestException('order.item_not_found');
            }
        } else {
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
            throw new BadRequestException('order.invalid_status_action');
        }
    }

    public function checkIfBought($userId, $itemId, $itemType)
    {
        switch ($itemType) {
            case OrderModel::ITEM_COURSE:
                $this->checkIfBoughtCourse($userId, $itemId);
                break;
            case OrderModel::ITEM_PACKAGE:
                $this->checkIfBoughtPackage($userId, $itemId);
                break;
        }
    }

    public function checkIfBoughtCourse($userId, $courseId)
    {
        $orderRepo = new OrderRepo();

        $itemType = OrderModel::ITEM_PACKAGE;

        $order = $orderRepo->findFinishedUserOrder($userId, $courseId, $itemType);

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

        $order = $orderRepo->findFinishedUserOrder($userId, $packageId, $itemType);

        if ($order) {
            throw new BadRequestException('order.has_bought_package');
        }
    }

}
