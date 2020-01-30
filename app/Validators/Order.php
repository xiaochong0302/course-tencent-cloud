<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Order as OrderModel;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\Order as OrderRepo;

class Order extends Validator
{

    /**
     * @param int $id
     * @return \App\Models\Order
     * @throws BadRequestException
     */
    public function checkOrder($id)
    {
        $orderRepo = new OrderRepo();

        $order = $orderRepo->findById($id);

        if (!$order) {
            throw new BadRequestException('order.not_found');
        }

        return $order;
    }

    public function checkItemId($id)
    {
        $value = $this->filter->sanitize($id, ['trim', 'int']);

        return $value;
    }

    public function checkItemType($type)
    {
        $scopes = [
            OrderModel::ITEM_TYPE_COURSE,
            OrderModel::ITEM_TYPE_PACKAGE,
            OrderModel::ITEM_TYPE_REWARD,
        ];

        if (!in_array($type, $scopes)) {
            throw new BadRequestException('order.invalid_item_type');
        }

        return $type;
    }

    public function checkAmount($amount)
    {
        $value = $this->filter->sanitize($amount, ['trim', 'float']);

        if ($value < 0.01) {
            throw new BadRequestException('order.invalid_pay_amount');
        }

        return $value;
    }

    public function checkStatus($status)
    {
        $scopes = [
            OrderModel::STATUS_PENDING,
            OrderModel::STATUS_FINISHED,
            OrderModel::STATUS_CLOSED,
            OrderModel::STATUS_REFUND,
        ];

        if (!in_array($status, $scopes)) {
            throw new BadRequestException('order.invalid_status');
        }

        return $status;
    }

    public function checkPayChannel($channel)
    {
        $scopes = [
            OrderModel::PAY_CHANNEL_ALIPAY,
            OrderModel::PAY_CHANNEL_WXPAY,
        ];

        if (!in_array($channel, $scopes)) {
            throw new BadRequestException('order.invalid_pay_channel');
        }

        return $channel;
    }

    public function checkDailyLimit($userId)
    {
        $orderRepo = new OrderRepo();

        $count = $orderRepo->countUserTodayOrders($userId);

        if ($count > 50) {
            throw new BadRequestException('order.reach_daily_limit');
        }
    }

    public function checkIfAllowPay($order)
    {
        if (time() - $order->created_at > 3600) {

            if ($order->status == OrderModel::STATUS_PENDING) {

                $order->status = OrderModel::STATUS_CLOSED;

                $order->update();
            }

            throw new BadRequestException('order.trade_expired');
        }

        if ($order->status != OrderModel::STATUS_PENDING) {
            throw new BadRequestException('order.invalid_status_action');
        }
    }

    public function checkIfAllowCancel($order)
    {
        if ($order->status != OrderModel::STATUS_PENDING) {
            throw new BadRequestException('order.invalid_status_action');
        }
    }

    public function checkIfBoughtCourse($userId, $courseId)
    {
        $courseUserRepo = new CourseUserRepo();

        $record = $courseUserRepo->find($userId, $courseId);

        if ($record) {

            $conditionA = $record->expire_time == 0;
            $conditionB = $record->expire_time > time();

            if ($conditionA || $conditionB) {
                throw new BadRequestException('order.has_bought_course');
            }
        }
    }

    public function checkIfBoughtPackage($userId, $packageId)
    {
        $orderRepo = new OrderRepo();

        $itemType = OrderModel::ITEM_TYPE_PACKAGE;

        $order = $orderRepo->findSuccessUserOrder($userId, $packageId, $itemType);

        if ($order) {
            throw new BadRequestException('order.has_bought_package');
        }
    }

}
