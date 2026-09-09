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
use App\Services\Logic\User\ShallowUserInfo;

class RefundInfo extends LogicService
{

    use RefundTrait;

    public function handle(string $sn): array
    {
        $refund = $this->checkRefundBySn($sn);

        $user = $this->getLoginUser(true);

        return $this->handleRefund($refund, $user);
    }

    protected function handleRefund(RefundModel $refund, UserModel $user): array
    {
        $statusHistory = $this->handleStatusHistory($refund->id);
        $order = $this->handleOrderInfo($refund->order_id);
        $owner = $this->handleOwnerInfo($refund->owner_id);
        $me = $this->handleMeInfo($refund, $user);

        return [
            'sn' => $refund->sn,
            'subject' => $refund->subject,
            'amount' => $refund->amount,
            'channel' => $refund->channel,
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

    protected function handleOrderInfo(int $orderId): array
    {
        $orderRepo = new OrderRepo();

        $order = $orderRepo->findById($orderId);

        return [
            'id' => $order->id,
            'sn' => $order->sn,
            'subject' => $order->subject,
            'amount' => $order->amount,
            'channel' => $order->channel,
            'status' => $order->status,
        ];
    }

    protected function handleOwnerInfo(int $userId): array
    {
        $service = new ShallowUserInfo();

        return $service->handle($userId);
    }

    protected function handleStatusHistory(int $refundId): array
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

    protected function handleMeInfo(RefundModel $refund, UserModel $user): array
    {
        $result = [
            'owned' => 0,
            'allow_cancel' => 0,
        ];

        if ($user->id == $refund->owner_id) {
            $result['owned'] = 1;
        }

        if ($refund->status == RefundModel::STATUS_PENDING) {
            $result['allow_cancel'] = 1;
        }

        return $result;
    }

}
