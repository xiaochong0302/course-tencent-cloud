<?php

namespace App\Services\Logic\User\Console;

use App\Library\Paginator\Query as PagerQuery;
use App\Models\PointRedeem as PointRedeemModel;
use App\Repos\PointRedeem as PointRedeemRepo;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\UserTrait;

class PointRedeemList extends LogicService
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

        /**
         * @var PointRedeemModel[] $redeems
         */
        $redeems = $pager->items;

        foreach ($redeems as $redeem) {
            $items[] = [
                'id' => $redeem->id,
                'status' => $redeem->status,
                'create_time' => $redeem->create_time,
                'update_time' => $redeem->update_time,
                'user' => [
                    'id' => $redeem->user_id,
                    'name' => $redeem->user_name,
                ],
                'gift' => [
                    'id' => $redeem->gift_id,
                    'name' => $redeem->gift_name,
                    'type' => $redeem->gift_type,
                    'point' => $redeem->gift_point,
                ],
                'contact' => [
                    'name' => $redeem->contact_name,
                    'phone' => $redeem->contact_phone,
                    'address' => $redeem->contact_address,
                ],
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
