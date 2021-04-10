<?php

namespace App\Services\Logic\Refund;

use App\Models\Refund as RefundModel;
use App\Repos\Order as OrderRepo;
use App\Repos\Refund as RefundRepo;
use App\Services\Logic\RefundTrait;
use App\Services\Logic\Service as LogicService;

class RefundInfo extends LogicService
{

    use RefundTrait;

    public function handle($sn)
    {
        $refund = $this->checkRefundBySn($sn);

        return $this->handleRefund($refund);
    }

    protected function handleRefund(RefundModel $refund)
    {
        $order = $this->handleOrderInfo($refund->order_id);

        $statusHistory = $this->handleStatusHistory($refund->id);

        return [
            'order' => $order,
            'sn' => $refund->sn,
            'subject' => $refund->subject,
            'amount' => $refund->amount,
            'status' => $refund->status,
            'status_history' => $statusHistory,
            'apply_note' => $refund->apply_note,
            'review_note' => $refund->review_note,
        ];
    }

    protected function handleOrderInfo($orderId)
    {
        $orderRepo = new OrderRepo();

        $order = $orderRepo->findById($orderId);

        return [
            'id' => $order->id,
            'sn' => $order->sn,
            'subject' => $order->subject,
            'amount' => $order->amount,
        ];
    }

    protected function handleStatusHistory($refundId)
    {
        $refundRepo = new RefundRepo();

        $records = $refundRepo->findStatusHistory($refundId);

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

}
