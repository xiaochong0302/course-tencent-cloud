<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\User\Console;

use App\Builders\OrderList as OrderListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Order as OrderRepo;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\UserTrait;
use App\Validators\Order as OrderValidator;

class OrderList extends LogicService
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

            $order['item_info'] = $builder->handleItemInfo($order);

            $me = $builder->handleMeInfo($order);

            $items[] = [
                'sn' => $order['sn'],
                'subject' => $order['subject'],
                'amount' => (float)$order['amount'],
                'status' => $order['status'],
                'item_id' => $order['item_id'],
                'item_type' => $order['item_type'],
                'item_info' => $order['item_info'],
                'create_time' => $order['create_time'],
                'update_time' => $order['update_time'],
                'me' => $me,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
