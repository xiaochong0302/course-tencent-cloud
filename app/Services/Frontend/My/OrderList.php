<?php

namespace App\Services\Frontend\My;

use App\Builders\OrderList as OrderListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Order as OrderRepo;
use App\Services\Frontend\Service as FrontendService;
use App\Services\Frontend\UserTrait;

class OrderList extends FrontendService
{

    use UserTrait;

    public function handle()
    {
        $user = $this->getLoginUser();

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['user_id'] = $user->id;
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
                'create_time' => $order['create_time'],
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
