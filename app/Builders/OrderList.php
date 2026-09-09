<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use App\Models\Course as CourseModel;
use App\Models\KgProduct as KgProductModel;
use App\Models\Order as OrderModel;

class OrderList extends Builder
{

    /**
     * @var string
     */
    protected string $imgBaseUrl;

    public function __construct()
    {
        $this->imgBaseUrl = kg_cos_url();
    }

    public function handleUsers(array $orders): array
    {
        $users = $this->getUsers($orders);

        foreach ($orders as $key => $order) {
            $orders[$key]['owner'] = $users[$order['owner_id']] ?? null;
        }

        return $orders;
    }

    public function handleItems(array $orders): array
    {
        foreach ($orders as $key => $order) {
            $itemInfo = $this->handleItemInfo($order);
            $orders[$key]['item_info'] = $itemInfo;
        }

        return $orders;
    }

    public function handleItemInfo(array $order): array
    {
        $itemInfo = [];

        switch ($order['item_type']) {
            case KgProductModel::ITEM_COURSE:
                $itemInfo = $this->handleCourseInfo($order['item_info']);
                break;
            case KgProductModel::ITEM_VIP:
                $itemInfo = $this->handleVipInfo($order['item_info']);
                break;
        }

        return $itemInfo;
    }

    public function handleMeInfo(array $order): array
    {
        $me = [
            'allow_pay' => 0,
            'allow_cancel' => 0,
            'allow_refund' => 0,
        ];

        $payStatusOk = $order['status'] == OrderModel::STATUS_PENDING ? 1 : 0;
        $cancelStatusOk = $order['status'] == OrderModel::STATUS_PENDING ? 1 : 0;
        $refundStatusOk = $order['status'] == OrderModel::STATUS_FINISHED ? 1 : 0;

        if ($order['item_type'] == KgProductModel::ITEM_COURSE) {

            $course = $order['item_info']['course'];

            $courseModelOk = $course['model'] != CourseModel::MODEL_OFFLINE;
            $refundTimeOk = $course['refund_expiry_time'] > time();

            $me['allow_refund'] = $courseModelOk && $refundStatusOk && $refundTimeOk ? 1 : 0;
        }

        if ($payStatusOk == 1) {
            $me['allow_pay'] = 1;
        }

        if ($cancelStatusOk == 1) {
            $me['allow_cancel'] = 1;
        }

        return $me;
    }

    protected function handleCourseInfo(string $itemInfo): array
    {
        $result = [];

        if (!empty($itemInfo)) {
            $result = json_decode($itemInfo, true);
            $result['course']['cover'] = $this->imgBaseUrl . $result['course']['cover'];
        }

        return $result;
    }

    protected function handleVipInfo(string $itemInfo): array
    {
        $result = [];

        if (!empty($itemInfo)) {
            $result = json_decode($itemInfo, true);
            $result['vip']['cover'] = $this->imgBaseUrl . $result['vip']['cover'];
        }

        return $result;
    }

    protected function getUsers(array $orders): array
    {
        $ids = kg_array_column($orders, 'owner_id');

        return $this->getShallowUserByIds($ids);
    }

}
