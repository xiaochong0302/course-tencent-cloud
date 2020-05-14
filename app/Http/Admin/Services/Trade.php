<?php

namespace App\Http\Admin\Services;

use App\Builders\TradeList as TradeListBuilder;
use App\Library\Paginator\Query as PaginateQuery;
use App\Models\Refund as RefundModel;
use App\Repos\Account as AccountRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\Trade as TradeRepo;
use App\Repos\User as UserRepo;
use App\Validators\Trade as TradeValidator;

class Trade extends Service
{

    public function getTrades()
    {
        $pageQuery = new PaginateQuery();

        $params = $pageQuery->getParams();

        /**
         * 兼容订单编号或订单序号查询
         */
        if (isset($params['order_id']) && strlen($params['order_id']) > 10) {
            $orderRepo = new OrderRepo();
            $order = $orderRepo->findBySn($params['order_id']);
            $params['order_id'] = $order ? $order->id : -1000;
        }

        $sort = $pageQuery->getSort();
        $page = $pageQuery->getPage();
        $limit = $pageQuery->getLimit();

        $tradeRepo = new TradeRepo();

        $pager = $tradeRepo->paginate($params, $sort, $page, $limit);

        return $this->handleTrades($pager);
    }

    public function getTrade($id)
    {
        $tradeRepo = new TradeRepo();

        return $tradeRepo->findById($id);
    }

    public function getOrder($orderId)
    {
        $orderRepo = new OrderRepo();

        return $orderRepo->findById($orderId);
    }

    public function getRefunds($tradeId)
    {
        $tradeRepo = new TradeRepo();

        return $tradeRepo->findRefunds($tradeId);
    }

    public function getUser($userId)
    {
        $userRepo = new UserRepo();

        return $userRepo->findById($userId);
    }

    public function getAccount($userId)
    {
        $accountRepo = new AccountRepo();

        return $accountRepo->findById($userId);
    }

    public function refundTrade($id)
    {
        $trade = $this->findOrFail($id);

        $validator = new TradeValidator();

        $validator->checkIfAllowRefund($trade);

        $refund = new RefundModel();

        $refund->subject = $trade->subject;
        $refund->amount = $trade->amount;
        $refund->user_id = $trade->user_id;
        $refund->order_id = $trade->order_id;
        $refund->trade_id = $trade->id;
        $refund->apply_note = '后台人工申请退款';

        $refund->create();

        return $trade;
    }

    protected function findOrFail($id)
    {
        $validator = new TradeValidator();

        return $validator->checkTrade($id);
    }

    protected function handleTrades($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new TradeListBuilder();

            $pipeA = $pager->items->toArray();
            $pipeB = $builder->handleUsers($pipeA);
            $pipeC = $builder->handleOrders($pipeB);
            $pipeD = $builder->objects($pipeC);

            $pager->items = $pipeD;
        }

        return $pager;
    }

}
