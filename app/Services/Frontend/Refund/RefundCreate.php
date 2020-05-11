<?php

namespace App\Services\Frontend\Refund;

use App\Models\Refund as RefundModel;
use App\Services\Frontend\OrderTrait;
use App\Services\Frontend\Service;
use App\Services\Refund as RefundService;
use App\Validators\Order as OrderValidator;
use App\Validators\Refund as RefundValidator;

class RefundCreate extends Service
{

    use OrderTrait;

    public function handle()
    {
        $post = $this->request->getPost();

        $order = $this->checkOrderBySn($post['order_sn']);

        $user = $this->getLoginUser();

        $validator = new OrderValidator();

        $validator->checkIfAllowRefund($order);

        $refundService = new RefundService();

        $refundAmount = $refundService->getRefundAmount($order);

        $validator = new RefundValidator();

        $validator->checkAmount($order->amount, $refundAmount);

        $applyNote = $validator->checkApplyNote($post['apply_note']);

        $refund = new RefundModel();

        $refund->subject = $order->subject;
        $refund->amount = $order->amount;
        $refund->apply_note = $applyNote;
        $refund->order_id = $order->id;
        $refund->trade_id = $order->id;
        $refund->user_id = $user->id;

        $refund->create();

        return $refund;
    }

}
