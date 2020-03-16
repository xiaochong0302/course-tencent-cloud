<?php

namespace App\Http\Admin\Services;

use App\Builders\TradeList as TradeListBuilder;
use App\Library\Paginator\Query as PaginateQuery;
use App\Models\Refund as RefundModel;
use App\Models\Trade as TradeModel;
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

        $trade = $tradeRepo->findById($id);

        return $trade;
    }

    public function getOrder($orderId)
    {
        $orderRepo = new OrderRepo();

        $order = $orderRepo->findById($orderId);

        return $order;
    }

    public function getRefunds($tradeId)
    {
        $tradeRepo = new TradeRepo();

        $refunds = $tradeRepo->findRefunds($tradeId);

        return $refunds;
    }

    public function getUser($userId)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($userId);

        return $user;
    }

    public function getAccount($userId)
    {
        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById($userId);

        return $account;
    }

    public function closeTrade($id)
    {
        $trade = $this->findOrFail($id);

        $validator = new TradeValidator();

        $validator->checkIfAllowClose($trade);

        $trade->status = TradeModel::STATUS_CLOSED;
        $trade->update();

        return $trade;
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
        $refund->trade_id = $trade->sn;
        $refund->apply_note = '后台人工申请退款';

        $refund->create();

        return $trade;
    }

    protected function findOrFail($id)
    {
        $validator = new TradeValidator();

        $result = $validator->checkTrade($id);

        return $result;
    }

    protected function handleTrades($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new TradeListBuilder();

            $pipeA = $pager->items->toArray();
            $pipeB = $builder->handleUsers($pipeA);
            $pipeC = $builder->handleOrders($pipeB);
            $pipeD = $builder->arrayToObject($pipeC);

            $pager->items = $pipeD;
        }

        return $pager;
    }

}
