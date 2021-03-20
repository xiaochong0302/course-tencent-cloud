<?php

namespace App\Services\Logic\User\Console;

use App\Builders\OrderList as OrderListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Order as OrderRepo;
use App\Services\Logic\Service;
use App\Services\Logic\UserTrait;
use App\Validators\Order as OrderValidator;

class OrderList extends Service
{

    use UserTrait;

    public function handle()
    {
        $user = $this->getLoginUser();

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $validator = new OrderValidator();

        if (!empty($params['status'])) {
            $params['status'] = $validator->checkStatus($params['status']);
        }

        $params['owner_id'] = $user->id;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $orderRepo = new OrderRepo();

        $pager = $orderRepo->paginate($params, $sort, $page, $limit);

        return $this->handleOrders($pager);
    }

    public function handleOrders($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $builder = new OrderListBuilder();

        $orders = $pager->items->toArray();

        $items = [];

        foreach ($orders as $order) {

            $order['item_info'] = $builder->handleItem($order);

            $items[] = [
                'sn' => $order['sn'],
                'subject' => $order['subject'],
                'amount' => (float)$order['amount'],
                'status' => $order['status'],
                'item_id' => $order['item_id'],
                'item_type' => $order['item_type'],
                'item_info' => $order['item_info'],
                'promotion_id' => $order['promotion_id'],
                'promotion_type' => $order['promotion_type'],
                'promotion_info' => $order['promotion_info'],
                'create_time' => $order['create_time'],
                'update_time' => $order['update_time'],
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
