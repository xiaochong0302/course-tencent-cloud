<?php

namespace App\Builders;

use App\Models\Order as OrderModel;
use App\Repos\User as UserRepo;

class OrderList extends Builder
{

    protected $imgBaseUrl;

    public function __construct()
    {
        $this->imgBaseUrl = kg_ci_base_url();
    }

    /**
     * @param array $orders
     * @return array
     */
    public function handleUsers(array $orders)
    {
        $users = $this->getUsers($orders);

        foreach ($orders as $key => $order) {
            $orders[$key]['user'] = $users[$order['user_id']] ?? new \stdClass();
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
            $itemInfo = $this->handleItem($order);
            $orders[$key]['item_info'] = $itemInfo;
        }

        return $orders;
    }

    /**
     * @param array $order
     * @return array|mixed
     */
    public function handleItem(array $order)
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
    protected function handleRewardInfo($itemInfo)
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
        $ids = kg_array_column($orders, 'user_id');

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($ids, ['id', 'name']);

        $result = [];

        foreach ($users->toArray() as $user) {
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
