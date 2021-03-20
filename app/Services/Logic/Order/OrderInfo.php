<?php

namespace App\Services\Logic\Order;

use App\Models\Order as OrderModel;
use App\Repos\Order as OrderRepo;
use App\Services\Logic\Service;
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

        $statusHistory = $this->handleStatusHistory($order->id);

        return [
            'sn' => $order->sn,
            'subject' => $order->subject,
            'amount' => $order->amount,
            'status' => $order->status,
            'status_history' => $statusHistory,
            'item_id' => $order->item_id,
            'item_type' => $order->item_type,
            'item_info' => $order->item_info,
            'promotion_id' => $order->promotion_id,
            'promotion_type' => $order->promotion_type,
            'promotion_info' => $order->promotion_info,
            'create_time' => $order->create_time,
            'update_time' => $order->update_time,
        ];
    }

    protected function handleStatusHistory($orderId)
    {
        $orderRepo = new OrderRepo();

        $records = $orderRepo->findStatusHistory($orderId);

        if ($records->count() == 0) {
            return [];
        }

        $result = [];

        foreach ($records as $record) {
            $result[] = [
                'status' => $record->status,
                'create_time' => $record->create_time,
            ];
        }

        return $result;
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
        $itemInfo['course']['cover'] = kg_cos_course_cover_url($itemInfo['course']['cover']);

        return $itemInfo;
    }

    protected function handlePackageInfo($itemInfo)
    {
        $baseUrl = kg_cos_url();

        foreach ($itemInfo['courses'] as &$course) {
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
