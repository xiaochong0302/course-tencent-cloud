<?php

namespace App\Services\Frontend\Refund;

use App\Models\Refund as RefundModel;
use App\Services\Frontend\RefundTrait;
use App\Services\Frontend\Service as FrontendService;

class RefundInfo extends FrontendService
{

    use RefundTrait;

    public function handle($sn)
    {
        $refund = $this->checkRefundBySn($sn);

        return $this->handleRefund($refund);
    }

    protected function handleRefund(RefundModel $refund)
    {
        return [
            'sn' => $refund->sn,
            'subject' => $refund->subject,
            'amount' => $refund->amount,
            'apply_note' => $refund->apply_note,
            'review_note' => $refund->review_note,
            'status' => $refund->status,
            'create_time' => $refund->create_time,
        ];
    }

}
