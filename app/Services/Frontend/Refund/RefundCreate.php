<?php

namespace App\Services\Frontend\Refund;

use App\Models\Refund as RefundModel;
use App\Models\Task;
use App\Models\Task as TaskModel;
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
        $refund->status = RefundModel::STATUS_APPROVED;
        $refund->review_note = '退款周期内无条件审批';

        $refund->create();

        $task = new TaskModel();

        /**
         * 设定延迟，给取消退款一个调解机会
         */
        $itemInfo = [
            'refund' => $refund->toArray(),
            'deadline' => time() + 3600 * 24 * 2,
        ];

        $task->item_id = $refund->id;
        $task->item_type = TaskModel::TYPE_REFUND;
        $task->item_info = $itemInfo;
        $task->priority = TaskModel::PRIORITY_MIDDLE;
        $task->status = TaskModel::STATUS_PENDING;

        $task->create();

        return $refund;
    }

}
