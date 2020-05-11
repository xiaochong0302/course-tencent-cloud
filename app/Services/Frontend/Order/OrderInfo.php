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
            'sn' => $order->sn,
            'subject' => $order->subject,
            'amount' => $order->amount,
            'status' => $order->status,
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

        $result = [];

        switch ($order->item_type) {
            case OrderModel::ITEM_COURSE:
                $result = $this->handleCourseInfo($itemInfo);
                break;
            case OrderModel::ITEM_PACKAGE:
                $result = $this->handlePackageInfo($itemInfo);
                break;
            case OrderModel::ITEM_VIP:
                $result = $this->handleVipInfo($itemInfo);
                break;
            case OrderModel::ITEM_REWARD:
                $result = $this->handleRewardInfo($itemInfo);
                break;
            case OrderModel::ITEM_TEST:
                $result = $this->handleTestInfo($itemInfo);
                break;
        }

        return $result ?: new \stdClass();
    }

    protected function handleCourseInfo($itemInfo)
    {
        $itemInfo['course']['cover'] = kg_ci_img_url($itemInfo['course']['cover']);

        return $itemInfo;
    }

    protected function handlePackageInfo($itemInfo)
    {
        $baseUrl = kg_ci_base_url();

        foreach ($itemInfo['package']['courses'] as &$course) {
            $course['cover'] = $baseUrl . $course['cover'];
        }

        return $itemInfo;
    }

    protected function handleVipInfo($itemInfo)
    {
        return $itemInfo;
    }

    protected function handleRewardInfo($itemInfo)
    {
        return $itemInfo;
    }

    protected function handleTestInfo($itemInfo)
    {
        return $itemInfo;
    }

}
