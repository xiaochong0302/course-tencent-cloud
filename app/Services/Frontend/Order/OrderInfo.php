<?php

namespace App\Services\Frontend\Order;

use App\Models\Order as OrderModel;
use App\Services\Frontend\Service;
use App\Validators\Order as OrderValidator;

class OrderInfo extends Service
{

    public function handle($sn)
    {
        $validator = new OrderValidator();

        $order = $validator->checkOrderBySn($sn);

        return $this->handleOrder($order);
    }

    protected function handleOrder(OrderModel $order)
    {
        $order->item_info = $this->handleItemInfo($order);

        return [
            'id' => $order->id,
            'sn' => $order->sn,
            'subject' => $order->subject,
            'amount' => $order->amount,
            'status' => $order->status,
            'user_id' => $order->user_id,
            'item_id' => $order->item_id,
            'item_type' => $order->item_type,
            'item_info' => $order->item_info,
            'create_time' => $order->create_time,
        ];
    }

    protected function handleItemInfo(OrderModel $order)
    {
        /**
         * @var array $itemInfo
         */
        $itemInfo = $order->item_info;

        if ($order->item_type == OrderModel::ITEM_COURSE) {

            return $this->handleCourseInfo($itemInfo);

        } elseif ($order->item_type == OrderModel::ITEM_PACKAGE) {

            return $this->handlePackageInfo($itemInfo);

        } elseif ($order->item_type == OrderModel::ITEM_VIP) {

            return $this->handleVipInfo($itemInfo);
        }

        return $itemInfo;
    }

    protected function handleCourseInfo(array $itemInfo)
    {
        $itemInfo['course']['cover'] = kg_ci_img_url($itemInfo['course']['cover']);

        return $itemInfo;
    }

    protected function handlePackageInfo(array $itemInfo)
    {
        $baseUrl = kg_ci_base_url();

        foreach ($itemInfo['package']['courses'] as &$course) {
            $course['cover'] = $baseUrl . $course['cover'];
        }

        return $itemInfo;
    }

    protected function handleVipInfo(array $itemInfo)
    {
        return $itemInfo;
    }

}
