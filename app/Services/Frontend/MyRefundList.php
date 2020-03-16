<?php

namespace App\Services\Frontend;

use App\Builders\RefundList as RefundListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Refund as RefundRepo;

class MyRefundList extends Service
{

    use UserTrait;

    public function getRefunds()
    {
        $user = $this->getLoginUser();

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['user_id'] = $user->id;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $refundRepo = new RefundRepo();

        $pager = $refundRepo->paginate($params, $sort, $page, $limit);

        return $this->handleRefunds($pager);
    }

    protected function handleRefunds($pager)
    {
        if ($pager->total_items == 0) {
            $pager->items = [];
            return $pager;
        }

        $builder = new RefundListBuilder();

        $refunds = $pager->items->toArray();

        $orders = $builder->getOrders($refunds);

        $items = [];

        foreach ($refunds as $refund) {

            $order = $orders[$refund['order_id']] ?? [];

            $items[] = [
                'order' => $order,
                'amount' => (float)$refund['amount'],
                'status' => $refund['status'],
                'apply_note' => $refund['apply_note'],
                'review_note' => $refund['review_note'],
                'created_at' => (int)$refund['created_at'],
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
