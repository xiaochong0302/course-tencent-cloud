<?php

namespace App\Services\Frontend\My;

use App\Builders\RefundList as RefundListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Refund as RefundRepo;
use App\Services\Frontend\Service as FrontendService;
use App\Services\Frontend\UserTrait;
use App\Validators\Refund as RefundValidator;

class RefundList extends FrontendService
{

    use UserTrait;

    public function handle()
    {
        $user = $this->getLoginUser();

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $validator = new RefundValidator();

        if (!empty($params['status'])) {
            $params['status'] = $validator->checkStatus($params['status']);
        }

        $params['owner_id'] = $user->id;
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
            return $pager;
        }

        $builder = new RefundListBuilder();

        $refunds = $pager->items->toArray();

        $orders = $builder->getOrders($refunds);

        $items = [];

        foreach ($refunds as $refund) {

            $order = $orders[$refund['order_id']] ?? new \stdClass();

            $items[] = [
                'order' => $order,
                'sn' => $refund['sn'],
                'subject' => $refund['subject'],
                'amount' => (float)$refund['amount'],
                'status' => $refund['status'],
                'apply_note' => $refund['apply_note'],
                'review_note' => $refund['review_note'],
                'create_time' => $refund['create_time'],
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
