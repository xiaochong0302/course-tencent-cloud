<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Refund;

use App\Models\Refund as RefundModel;
use App\Models\Task as TaskModel;
use App\Repos\Order as OrderRepo;
use App\Services\Logic\OrderTrait;
use App\Services\Logic\Service as LogicService;
use App\Services\Refund as RefundService;
use App\Validators\Order as OrderValidator;
use App\Validators\Refund as RefundValidator;

class RefundCreate extends LogicService
{

    use OrderTrait;

    public function handle()
    {
        $logger = $this->getLogger('refund');

        $post = $this->request->getPost();

        $order = $this->checkOrderBySn($post['order_sn']);

        $user = $this->getLoginUser();

        $orderRepo = new OrderRepo();

        $trade = $orderRepo->findLastTrade($order->id);

        $validator = new OrderValidator();

        $validator->checkOwner($user->id, $order->owner_id);

        $validator->checkIfAllowRefund($order);

        $refundService = new RefundService();

        $preview = $refundService->preview($order);

        $refundAmount = $preview['refund_amount'];

        $validator = new RefundValidator();

        $applyNote = $validator->checkApplyNote($post['apply_note']);

        $validator->checkAmount($order->amount, $refundAmount);

        try {

            $this->db->begin();

            $refund = new RefundModel();

            $refund->subject = $order->subject;
            $refund->amount = $refundAmount;
            $refund->apply_note = $applyNote;
            $refund->order_id = $order->id;
            $refund->trade_id = $trade->id;
            $refund->owner_id = $user->id;
            $refund->status = RefundModel::STATUS_APPROVED;
            $refund->review_note = '退款周期内无条件审批';

            if ($refund->create() === false) {
                throw new \RuntimeException('Create Refund Failed');
            }

            $task = new TaskModel();

            $itemInfo = [
                'refund' => ['id' => $refund->id],
            ];

            $task->item_id = $refund->id;
            $task->item_type = TaskModel::TYPE_REFUND;
            $task->item_info = $itemInfo;
            $task->priority = TaskModel::PRIORITY_MIDDLE;
            $task->status = TaskModel::STATUS_PENDING;

            if ($task->create() === false) {
                throw new \RuntimeException('Create Refund Task Failed');
            }

            $this->db->commit();

            return $refund;

        } catch (\Exception $e) {

            $this->db->rollback();

            $logger->error('Create Refund Exception ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                ]));

            throw new \RuntimeException('sys.trans_rollback');
        }
    }

}
