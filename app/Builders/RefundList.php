<?php

namespace App\Builders;

use App\Repos\Order as OrderRepo;
use App\Repos\User as UserRepo;

class RefundList extends Builder
{

    public function handleOrders($trades)
    {
        $orders = $this->getOrders($trades);

        foreach ($trades as $key => $trade) {
            $trades[$key]['order'] = $orders[$trade['order_id']];
        }

        return $trades;
    }

    public function handleUsers($refunds)
    {
        $users = $this->getUsers($refunds);

        foreach ($refunds as $key => $refund) {
            $refunds[$key]['user'] = $users[$refund['user_id']];
        }

        return $refunds;
    }

    public function getOrders($trades)
    {
        $ids = kg_array_column($trades, 'order_id');

        $orderRepo = new OrderRepo();

        $orders = $orderRepo->findByIds($ids, ['id', 'sn', 'subject', 'amount']);

        $result = [];

        foreach ($orders->toArray() as $order) {
            $result[$order['id']] = $order;
        }

        return $result;
    }

    public function getUsers($refunds)
    {
        $ids = kg_array_column($refunds, 'user_id');

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($ids, ['id', 'name']);

        $result = [];

        foreach ($users->toArray() as $user) {
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
