<?php

namespace App\Services\Frontend\Refund;

use App\Models\Refund as RefundModel;
use App\Services\Frontend\OrderTrait;
use App\Services\Frontend\Service;
use App\Services\RefundCalculator;

class RefundConfirm extends Service
{

    use OrderTrait;

    public function handle($sn)
    {
        $order = $this->checkOrderBySn($sn);

        $service = new RefundCalculator();

        return $service->handle($order);
    }

    protected function handleRefund(RefundModel $refund)
    {
        return [
            'sn' => $refund->sn,
            'subject' => $refund->subject,
            'amount' => $refund->amount,
            'status' => $refund->status,
            'apply_note' => $refund->apply_note,
            'review_note' => $refund->review_note,
            'create_time' => $refund->create_time,
        ];
    }

}
