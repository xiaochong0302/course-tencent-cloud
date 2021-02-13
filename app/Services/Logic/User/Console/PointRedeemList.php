<?php

namespace App\Services\Logic\User\Console;

use App\Library\Paginator\Query as PagerQuery;
use App\Repos\PointRedeem as PointRedeemRepo;
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

        $redeemRepo = new PointRedeemRepo();

        $pager = $redeemRepo->paginate($params, $sort, $page, $limit);

        return $this->handlePager($pager);
    }

    public function handlePager($pager)
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
                    'name' => $item->gift_name,
                    'type' => $item->gift_type,
                    'point' => $item->gift_point,
                ],
                'contact' => [
                    'name' => $item->contact_name,
                    'phone' => $item->contact_phone,
                    'address' => $item->contact_address,
                ],
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
