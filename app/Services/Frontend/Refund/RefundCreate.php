<?php

namespace App\Services\Frontend\Refund;

use App\Models\Refund as RefundModel;
use App\Repos\Order as OrderRepo;
use App\Services\Frontend\OrderTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Services\Refund as RefundService;
use App\Validators\Order as OrderValidator;
use App\Validators\Refund as RefundValidator;

class RefundCreate extends FrontendService
{

    use OrderTrait;

    public function handle()
    {
        $post = $this->request->getPost();

        $order = $this->checkOrderBySn($post['order_sn']);

        $user = $this->getLoginUser();

        $orderRepo = new OrderRepo();

        $trade = $orderRepo->findLastTrade($order->id);

        $validator = new OrderValidator();

        $validator->checkOwner($user->id, $order->user_id);

        $validator->checkIfAllowRefund($order);

        $refundService = new RefundService();

        $preview = $refundService->preview($order);

        $refundAmount = $preview['refund_amount'];

        $validator = new RefundValidator();

        $applyNote = $validator->checkApplyNote($post['apply_note']);

        $validator->checkAmount($order->amount, $refundAmount);

        $refund = new RefundModel();

        $refund->subject = $order->subject;
        $refund->amount = $refundAmount;
        $refund->apply_note = $applyNote;
        $refund->order_id = $order->id;
        $refund->trade_id = $trade->id;
        $refund->user_id = $user->id;

        $refund->create();

        return $refund;
    }

}
