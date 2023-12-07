<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Builders\TradeList as TradeListBuilder;
use App\Library\Paginator\Query as PaginateQuery;
use App\Library\Validators\Common as CommonValidator;
use App\Models\Refund as RefundModel;
use App\Models\Trade as TradeModel;
use App\Repos\Account as AccountRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\Trade as TradeRepo;
use App\Repos\User as UserRepo;
use App\Validators\Refund as RefundValidator;
use App\Validators\Trade as TradeValidator;

class Trade extends Service
{

    public function getChannelTypes()
    {
        return TradeModel::channelTypes();
    }

    public function getStatusTypes()
    {
        return TradeModel::statusTypes();
    }

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

        $accountRepo = new AccountRepo();

        /**
         * 兼容用户编号｜手机号码｜邮箱地址查询
         */
        if (!empty($params['owner_id'])) {
            if (CommonValidator::phone($params['owner_id'])) {
                $account = $accountRepo->findByPhone($params['owner_id']);
                $params['owner_id'] = $account ? $account->id : -1000;
            } elseif (CommonValidator::email($params['owner_id'])) {
                $account = $accountRepo->findByEmail($params['owner_id']);
                $params['owner_id'] = $account ? $account->id : -1000;
            }
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

    public function getStatusHistory($id)
    {
        $tradeRepo = new TradeRepo();

        return $tradeRepo->findStatusHistory($id);
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

    public function confirmRefund($tradeId)
    {
        $trade = $this->findOrFail($tradeId);

        $orderRepo = new OrderRepo();

        $order = $orderRepo->findById($trade->order_id);

        $refund = new \App\Services\Refund();

        return $refund->preview($order);
    }

    public function refundTrade($id)
    {
        $trade = $this->findOrFail($id);

        $user = $this->getLoginUser();

        $post = $this->request->getPost();

        $validator = new TradeValidator();

        $validator->checkIfAllowRefund($trade);

        $validator = new RefundValidator();

        $applyNote = $validator->checkApplyNote($post['apply_note']);

        $refundAmount = $validator->checkAmount($trade->amount, $post['refund_amount']);

        $applyNote = sprintf('%s - 操作员（%s）', $applyNote, $user->id);

        $refund = new RefundModel();

        $refund->amount = $refundAmount;
        $refund->subject = $trade->subject;
        $refund->owner_id = $trade->owner_id;
        $refund->order_id = $trade->order_id;
        $refund->trade_id = $trade->id;
        $refund->apply_note = $applyNote;

        $refund->create();

        return $refund;
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
