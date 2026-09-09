<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Order;

use App\Models\Course as CourseModel;
use App\Models\KgProduct as KgProductModel;
use App\Models\Order as OrderModel;
use App\Models\User as UserModel;
use App\Repos\Order as OrderRepo;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\User\ShallowUserInfo;
use App\Validators\Order as OrderValidator;

class OrderInfo extends LogicService
{

    public function handle(string $sn): array
    {
        $validator = new OrderValidator();

        $order = $validator->checkBySn($sn);

        $user = $this->getLoginUser(true);

        return $this->handleOrder($order, $user);
    }

    protected function handleOrder(OrderModel $order, UserModel $user): array
    {
        $itemInfo = $this->handleItemInfo($order);

        $result = [
            'sn' => $order->sn,
            'subject' => $order->subject,
            'amount' => $order->amount,
            'channel' => $order->channel,
            'status' => $order->status,
            'deleted' => $order->deleted,
            'item_id' => $order->item_id,
            'item_type' => $order->item_type,
            'item_info' => $itemInfo,
            'create_time' => $order->create_time,
            'update_time' => $order->update_time,
        ];

        $result['status_history'] = $this->handleStatusHistory($order->id);
        $result['owner'] = $this->handleOwnerInfo($order->owner_id);
        $result['me'] = $this->handleMeInfo($order, $user);

        return $result;
    }

    protected function handleStatusHistory(int $orderId): array
    {
        $orderRepo = new OrderRepo();

        $records = $orderRepo->findStatusHistory($orderId);

        if ($records->count() == 0) return [];

        $result = [];

        foreach ($records as $record) {
            $result[] = [
                'status' => $record->status,
                'create_time' => $record->create_time,
            ];
        }

        return $result;
    }

    protected function handleOwnerInfo(int $userId): array
    {
        $service = new ShallowUserInfo();

        return $service->handle($userId);
    }

    protected function handleMeInfo(OrderModel $order, UserModel $user): array
    {
        $result = [
            'owned' => 0,
            'allow_pay' => 0,
            'allow_cancel' => 0,
            'allow_refund' => 0,
        ];

        if ($user->id == $order->owner_id) {
            $result['owned'] = 1;
        }

        if ($order->status == OrderModel::STATUS_PENDING) {
            $result['allow_pay'] = 1;
            $result['allow_cancel'] = 1;
        }

        if ($order->status == OrderModel::STATUS_FINISHED) {
            if ($order->item_type == KgProductModel::ITEM_COURSE) {
                $course = $order->item_info['course'];
                $refundTimeOk = $course['refund_expiry_time'] > time();
                $courseModelOk = $course['model'] != CourseModel::MODEL_OFFLINE;
                if ($refundTimeOk && $courseModelOk) {
                    $result['allow_refund'] = 1;
                }
            }
        }

        return $result;
    }

    protected function handleItemInfo(OrderModel $order): array
    {
        $result = [];

        switch ($order->item_type) {
            case KgProductModel::ITEM_COURSE:
                $result = $this->handleCourseInfo($order->item_info);
                break;
            case KgProductModel::ITEM_VIP:
                $result = $this->handleVipInfo($order->item_info);
                break;
            case KgProductModel::ITEM_PAY_TEST:
                $result = $this->handleTestInfo($order->item_info);
                break;
        }

        return $result;
    }

    protected function handleCourseInfo(array $itemInfo): array
    {
        /**
         * 数据中可能没有cover属性，避免读取出错
         */
        $cover = $itemInfo['course']['cover'] ?? null;

        $itemInfo['course']['cover'] = kg_cos_course_cover_url($cover);

        return $itemInfo;
    }

    protected function handleVipInfo(array $itemInfo): array
    {
        /**
         * 数据中可能没有cover属性，避免读取出错
         */
        $cover = $itemInfo['vip']['cover'] ?? null;

        $itemInfo['vip']['cover'] = kg_cos_vip_cover_url($cover);

        return $itemInfo;
    }

    protected function handleTestInfo(array $itemInfo): array
    {
        return $itemInfo;
    }

}
