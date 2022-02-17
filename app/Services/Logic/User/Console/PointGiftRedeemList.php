<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\User\Console;

use App\Library\Paginator\Query as PagerQuery;
use App\Models\PointGiftRedeem as PointGiftRedeemModel;
use App\Repos\PointGiftRedeem as PointGiftRedeemRepo;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\UserTrait;

class PointGiftRedeemList extends LogicService
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

        $redeemRepo = new PointGiftRedeemRepo();

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
         * @var PointGiftRedeemModel[] $redeems
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
