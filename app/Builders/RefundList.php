<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use App\Models\Refund as RefundModel;
use App\Repos\Order as OrderRepo;

class RefundList extends Builder
{

    public function handleOrders(array $trades)
    {
        $orders = $this->getOrders($trades);

        foreach ($trades as $key => $trade) {
            $trades[$key]['order'] = $orders[$trade['order_id']] ?? null;
        }

        return $trades;
    }

    public function handleUsers(array $refunds)
    {
        $users = $this->getUsers($refunds);

        foreach ($refunds as $key => $refund) {
            $refunds[$key]['owner'] = $users[$refund['owner_id']] ?? null;
        }

        return $refunds;
    }

    public function handleMeInfo(array $refund)
    {
        $me = [
            'allow_cancel' => 0,
        ];

        $statusTypes = [
            RefundModel::STATUS_PENDING,
            RefundModel::STATUS_APPROVED,
        ];

        if (in_array($refund['status'], $statusTypes)) {
            $me['allow_cancel'] = 1;
        }

        return $me;
    }

    public function getOrders(array $trades)
    {
        $ids = kg_array_column($trades, 'order_id');

        $orderRepo = new OrderRepo();

        $orders = $orderRepo->findByIds($ids, ['id', 'sn', 'subject', 'amount']);

        $result = [];

        foreach ($orders->toArray() as $order) {
            $result[$order['id']] = $order;
        }

        return $result;
    }

    public function getUsers(array $refunds)
    {
        $ids = kg_array_column($refunds, 'owner_id');

        return $this->getShallowUserByIds($ids);
    }

}
