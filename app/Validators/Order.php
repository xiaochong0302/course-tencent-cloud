<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Order as OrderModel;
use App\Models\Refund as RefundModel;
use App\Models\Trade as TradeModel;
use App\Repos\Order as OrderRepo;

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

    public function checkItemType($type)
    {
        $types = OrderModel::itemTypes();

        if (!array_key_exists($type, $types)) {
            throw new BadRequestException('order.invalid_item_type');
        }

        return $type;
    }

    public function checkCourse($id)
    {
        $validator = new Course();

        return $validator->checkCourse($id);
    }

    public function checkPackage($id)
    {
        $validator = new Package();

        return $validator->checkPackage($id);
    }

    public function checkVip($id)
    {
        $validator = new Vip();

        return $validator->checkVip($id);
    }

    public function checkAmount($amount)
    {
        $value = $this->filter->sanitize($amount, ['trim', 'float']);

        if ($value < 0.01 || $value > 100000) {
            throw new BadRequestException('order.invalid_amount');
        }

        return $value;
    }

    public function checkStatus($status)
    {
        $list = OrderModel::statusTypes();

        if (!array_key_exists($status, $list)) {
            throw new BadRequestException('order.invalid_status');
        }

        return $status;
    }

    public function checkIfAllowPay(OrderModel $order)
    {
        if ($order->status != OrderModel::STATUS_PENDING) {
            throw new BadRequestException('order.pay_not_allowed');
        }
    }

    public function checkIfAllowCancel(OrderModel $order)
    {
        if ($order->status != OrderModel::STATUS_PENDING) {
            throw new BadRequestException('order.cancel_not_allowed');
        }
    }

    public function checkIfAllowRefund(OrderModel $order)
    {
        if ($order->status != OrderModel::STATUS_FINISHED) {
            throw new BadRequestException('order.refund_not_allowed');
        }

        $types = [
            OrderModel::ITEM_COURSE,
            OrderModel::ITEM_PACKAGE,
        ];

        if (!in_array($order->item_type, $types)) {
            throw new BadRequestException('order.refund_not_supported');
        }

        $orderRepo = new OrderRepo();

        $trade = $orderRepo->findLastTrade($order->id);

        if ($trade->status != TradeModel::STATUS_FINISHED) {
            throw new BadRequestException('order.refund_not_allowed');
        }

        $refund = $orderRepo->findLastRefund($order->id);

        $scopes = [
            RefundModel::STATUS_PENDING,
            RefundModel::STATUS_APPROVED,
        ];

        if ($refund && in_array($refund->status, $scopes)) {
            throw new BadRequestException('order.refund_request_existed');
        }
    }

    public function checkIfBoughtCourse($userId, $courseId)
    {
        $orderRepo = new OrderRepo();

        $itemType = OrderModel::ITEM_COURSE;

        $order = $orderRepo->findUserLastDeliveringOrder($userId, $courseId, $itemType);

        if ($order) {
            throw new BadRequestException('order.is_delivering');
        }

        $order = $orderRepo->findUserLastFinishedOrder($userId, $courseId, $itemType);

        if ($order && $order->item_info['course']['study_expiry_time'] > time()) {
            throw new BadRequestException('order.has_bought_course');
        }
    }

    public function checkIfBoughtPackage($userId, $packageId)
    {
        $orderRepo = new OrderRepo();

        $itemType = OrderModel::ITEM_PACKAGE;

        $order = $orderRepo->findUserLastDeliveringOrder($userId, $packageId, $itemType);

        if ($order) {
            throw new BadRequestException('order.is_delivering');
        }

        $order = $orderRepo->findUserLastFinishedOrder($userId, $packageId, $itemType);

        if ($order) {
            throw new BadRequestException('order.has_bought_package');
        }
    }

}
