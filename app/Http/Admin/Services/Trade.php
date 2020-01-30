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

    public function getOrder($sn)
    {
        $orderRepo = new OrderRepo();

        $order = $orderRepo->findBySn($sn);

        return $order;
    }

    public function getRefunds($sn)
    {
        $tradeRepo = new TradeRepo();

        $refunds = $tradeRepo->findRefunds($sn);

        return $refunds;
    }

    public function getUser($id)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($id);

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
        $refund->order_sn = $trade->order_sn;
        $refund->trade_sn = $trade->sn;
        $refund->apply_reason = '后台人工申请退款';

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
            $pipeC = $builder->arrayToObject($pipeB);

            $pager->items = $pipeC;
        }

        return $pager;
    }

}
