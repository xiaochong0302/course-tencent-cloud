<?php

namespace App\Builders;

use App\Repos\Order as OrderRepo;
use App\Repos\User as UserRepo;

class TradeList extends Builder
{

    public function handleOrders(array $trades)
    {
        $orders = $this->getOrders($trades);

        foreach ($trades as $key => $trade) {
            $trades[$key]['order'] = $orders[$trade['order_id']] ?? new \stdClass();
        }

        return $trades;
    }

    public function handleUsers($trades)
    {
        $users = $this->getUsers($trades);

        foreach ($trades as $key => $trade) {
            $trades[$key]['user'] = $users[$trade['user_id']] ?? new \stdClass();
        }

        return $trades;
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

    public function getUsers($trades)
    {
        $ids = kg_array_column($trades, 'user_id');

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($ids, ['id', 'name']);

        $result = [];

        foreach ($users->toArray() as $user) {
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
