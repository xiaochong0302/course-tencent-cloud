<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use App\Models\Course as CourseModel;
use App\Models\Order as OrderModel;

class OrderList extends Builder
{

    protected $imgBaseUrl;

    public function __construct()
    {
        $this->imgBaseUrl = kg_cos_url();
    }

    /**
     * @param array $orders
     * @return array
     */
    public function handleUsers(array $orders)
    {
        $users = $this->getUsers($orders);

        foreach ($orders as $key => $order) {
            $orders[$key]['owner'] = $users[$order['owner_id']] ?? null;
        }

        return $orders;
    }

    /**
     * @param array $orders
     * @return array
     */
    public function handleItems(array $orders)
    {
        foreach ($orders as $key => $order) {
            $itemInfo = $this->handleItemInfo($order);
            $orders[$key]['item_info'] = $itemInfo;
        }

        return $orders;
    }

    /**
     * @param array $order
     * @return array|mixed
     */
    public function handleItemInfo(array $order)
    {
        $itemInfo = [];

        switch ($order['item_type']) {
            case OrderModel::ITEM_COURSE:
                $itemInfo = $this->handleCourseInfo($order['item_info']);
                break;
            case OrderModel::ITEM_PACKAGE:
                $itemInfo = $this->handlePackageInfo($order['item_info']);
                break;
            case OrderModel::ITEM_VIP:
                $itemInfo = $this->handleVipInfo($order['item_info']);
                break;
        }

        return $itemInfo;
    }

    /**
     * @param array $order
     * @return array|mixed
     */
    public function handleMeInfo(array $order)
    {
        $me = [
            'allow_pay' => 0,
            'allow_cancel' => 0,
            'allow_refund' => 0,
        ];

        $payStatusOk = $order['status'] == OrderModel::STATUS_PENDING ? 1 : 0;
        $cancelStatusOk = $order['status'] == OrderModel::STATUS_PENDING ? 1 : 0;
        $refundStatusOk = $order['status'] == OrderModel::STATUS_FINISHED ? 1 : 0;

        if ($order['item_type'] == OrderModel::ITEM_COURSE) {

            $course = $order['item_info']['course'];

            $courseModelOk = $course['model'] != CourseModel::MODEL_OFFLINE;
            $refundTimeOk = $course['refund_expiry_time'] > time();

            $me['allow_refund'] = $courseModelOk && $refundStatusOk && $refundTimeOk ? 1 : 0;

        } elseif ($order['item_type'] == OrderModel::ITEM_PACKAGE) {

            $courses = $order['item_info']['courses'];

            $refundTimeOk = false;

            foreach ($courses as $course) {
                if ($course['refund_expiry_time'] > time()) {
                    $refundTimeOk = true;
                }
            }

            $me['allow_refund'] = $refundStatusOk && $refundTimeOk ? 1 : 0;
        }

        if ($payStatusOk == 1) {
            $me['allow_pay'] = 1;
        }

        if ($cancelStatusOk == 1) {
            $me['allow_cancel'] = 1;
        }

        return $me;
    }

    /**
     * @param string $itemInfo
     * @return mixed
     */
    protected function handleCourseInfo($itemInfo)
    {
        if (!empty($itemInfo) && is_string($itemInfo)) {
            $itemInfo = json_decode($itemInfo, true);
            $itemInfo['course']['cover'] = $this->imgBaseUrl . $itemInfo['course']['cover'];
        }

        return $itemInfo;
    }

    /**
     * @param string $itemInfo
     * @return mixed
     */
    protected function handlePackageInfo($itemInfo)
    {
        if (!empty($itemInfo) && is_string($itemInfo)) {
            $itemInfo = json_decode($itemInfo, true);
            foreach ($itemInfo['courses'] as $key => $course) {
                $itemInfo['courses'][$key]['cover'] = $this->imgBaseUrl . $course['cover'];
            }
        }

        return $itemInfo;
    }

    /**
     * @param string $itemInfo
     * @return mixed
     */
    protected function handleVipInfo($itemInfo)
    {
        if (!empty($itemInfo) && is_string($itemInfo)) {
            $itemInfo = json_decode($itemInfo, true);
        }

        return $itemInfo;
    }

    /**
     * @param array $orders
     * @return array
     */
    protected function getUsers(array $orders)
    {
        $ids = kg_array_column($orders, 'owner_id');

        return $this->getShallowUserByIds($ids);
    }

}
