<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Refund;

use App\Models\Refund as RefundModel;
use App\Models\User as UserModel;
use App\Repos\Order as OrderRepo;
use App\Repos\Refund as RefundRepo;
use App\Services\Logic\RefundTrait;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\UserTrait;

class RefundInfo extends LogicService
{

    use RefundTrait;
    use UserTrait;

    public function handle($sn)
    {
        $refund = $this->checkRefundBySn($sn);

        $user = $this->getLoginUser();

        return $this->handleRefund($refund, $user);
    }

    protected function handleRefund(RefundModel $refund, UserModel $user)
    {
        $statusHistory = $this->handleStatusHistory($refund->id);
        $order = $this->handleOrderInfo($refund->order_id);
        $owner = $this->handleShallowUserInfo($refund->owner_id);
        $me = $this->handleMeInfo($refund, $user);

        return [
            'sn' => $refund->sn,
            'subject' => $refund->subject,
            'amount' => $refund->amount,
            'status' => $refund->status,
            'deleted' => $refund->deleted,
            'apply_note' => $refund->apply_note,
            'review_note' => $refund->review_note,
            'create_time' => $refund->create_time,
            'update_time' => $refund->update_time,
            'status_history' => $statusHistory,
            'order' => $order,
            'owner' => $owner,
            'me' => $me,
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

    protected function handleMeInfo(RefundModel $refund, UserModel $user)
    {
        $result = [
            'owned' => 0,
            'allow_cancel' => 0,
        ];

        if ($user->id == $refund->owner_id) {
            $result['owned'] = 1;
        }

        $statusTypes = [
            RefundModel::STATUS_PENDING,
            RefundModel::STATUS_APPROVED,
        ];

        if (in_array($refund->status, $statusTypes)) {
            $result['allow_cancel'] = 1;
        }

        return $result;
    }

}
