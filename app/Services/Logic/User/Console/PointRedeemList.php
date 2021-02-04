<?php

namespace App\Services\Logic\User\Console;

use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Order as OrderRepo;
use App\Services\Logic\Service;
use App\Services\Logic\UserTrait;

class PointRedeemList extends Service
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

        $items = [];

        foreach ($pager->items as $item) {
            $items[] = [
                'id' => $item->id,
                'status' => $item->status,
                'create_time' => $item->create_time,
                'user' => [
                    'id' => $item->user_id,
                    'name' => $item->user_name,
                ],
                'gift' => [
                    'id' => $item->gift_id,
                    'type' => $item->gift_type,
                    'name' => $item->gift_name,
                    'point' => $item->gift_point,
                ],
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
