<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Point;

use App\Library\Paginator\Query as PagerQuery;
use App\Repos\PointGift as PointGiftRepo;
use App\Services\Logic\Service as LogicService;

class GiftList extends LogicService
{

    public function handle()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['published'] = 1;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $giftRepo = new PointGiftRepo();

        $pager = $giftRepo->paginate($params, $sort, $page, $limit);

        return $this->handleGifts($pager);
    }

    protected function handleGifts($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $baseUrl = kg_cos_url();

        $items = [];

        foreach ($pager->items->toArray() as $gift) {

            $gift['cover'] = $baseUrl . $gift['cover'];

            $items[] = [
                'id' => $gift['id'],
                'name' => $gift['name'],
                'cover' => $gift['cover'],
                'details' => $gift['details'],
                'type' => $gift['type'],
                'point' => $gift['point'],
                'redeem_count' => $gift['redeem_count'],
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
