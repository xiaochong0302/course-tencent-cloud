<?php

namespace App\Builders;

use App\Models\Order as OrderModel;
use App\Repos\User as UserRepo;

class OrderList extends Builder
{
    protected $imgBaseUrl;

    public function __construct()
    {
        $this->imgBaseUrl = kg_img_base_url();
    }

    public function handleItems($orders)
    {
        $itemInfo = [];

        foreach ($orders as $key => $order) {
            switch ($order['item_type']) {
                case OrderModel::TYPE_COURSE:
                    $itemInfo = $this->handleCourseInfo($order['item_info']);
                    break;
                case OrderModel::TYPE_PACKAGE:
                    $itemInfo = $this->handlePackageInfo($order['item_info']);
                    break;
                case OrderModel::TYPE_REWARD:
                    $itemInfo = $this->handleRewardInfo($order['item_info']);
                    break;
            }
            $orders[$key]['item_info'] = $itemInfo;
        }

        return $orders;
    }

    protected function handleCourseInfo($itemInfo)
    {
        if (!empty($itemInfo) && is_string($itemInfo)) {
            $itemInfo = json_decode($itemInfo, true);
            $itemInfo['course']['cover'] = $this->imgBaseUrl . $itemInfo['course']['cover'];
        }

        return $itemInfo;
    }

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

    protected function handleRewardInfo($itemInfo)
    {
        if (!empty($itemInfo) && is_string($itemInfo)) {
            $itemInfo = json_decode($itemInfo, true);
            $itemInfo['course']['cover'] = $this->imgBaseUrl . $itemInfo['course']['cover'];
        }

        return $itemInfo;
    }

    public function handleUsers($orders)
    {
        $users = $this->getUsers($orders);

        foreach ($orders as $key => $order) {
            $orders[$key]['user'] = $users[$order['user_id']];
        }

        return $orders;
    }

    protected function getUsers($orders)
    {
        $ids = kg_array_column($orders, 'user_id');

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($ids, ['id', 'name', 'avatar'])->toArray();

        $result = [];

        foreach ($users as $user) {
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
