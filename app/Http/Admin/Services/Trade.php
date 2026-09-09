<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Builders\TradeList as TradeListBuilder;
use App\Http\Admin\Services\Traits\AccountSearchTrait;
use App\Http\Admin\Services\Traits\OrderSearchTrait;
use App\Http\Admin\Services\Traits\TradeSearchTrait;
use App\Library\Paginator\Query as PaginateQuery;
use App\Models\Account as AccountModel;
use App\Models\KgPayment as KgPaymentModel;
use App\Models\Order as OrderModel;
use App\Models\Refund as RefundModel;
use App\Models\Trade as TradeModel;
use App\Models\User as UserModel;
use App\Repos\Account as AccountRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\Trade as TradeRepo;
use App\Repos\User as UserRepo;
use App\Services\Refund as RefundService;
use App\Validators\Refund as RefundValidator;
use App\Validators\Trade as TradeValidator;
use Phalcon\Paginator\RepositoryInterface as PagerRepoInterface;

class Trade extends Service
{

    use AccountSearchTrait;
    use OrderSearchTrait;
    use TradeSearchTrait;

    public function allowRefund(TradeModel $trade): bool
    {
        return $trade->channel != KgPaymentModel::CHANNEL_APPLE_PAY;
    }

    public function getChannelTypes(): array
    {
        return TradeModel::channelTypes();
    }

    public function getStatusTypes(): array
    {
        return TradeModel::statusTypes();
    }

    public function getStatusHistory(int $tradeId)
    {
        $tradeRepo = new TradeRepo();

        return $tradeRepo->findStatusHistory($tradeId);
    }

    public function getRefunds(int $tradeId)
    {
        $tradeRepo = new TradeRepo();

        return $tradeRepo->findRefunds($tradeId);
    }

    public function confirmRefund(int $tradeId): array
    {
        $trade = $this->findOrFail($tradeId);

        $orderRepo = new OrderRepo();

        $order = $orderRepo->findById($trade->order_id);

        $refund = new RefundService();

        return $refund->preview($order);
    }

    public function getTrades(): PagerRepoInterface
    {
        $pageQuery = new PaginateQuery();

        $params = $pageQuery->getParams();

        $params = $this->handleAccountSearchParams($params);
        $params = $this->handleOrderSearchParams($params);
        $params = $this->handleTradeSearchParams($params);

        if (!empty($params['trade_id'])) {
            $params['id'] = $params['trade_id'];
        }

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pageQuery->getSort();
        $page = $pageQuery->getPage();
        $limit = $pageQuery->getLimit();

        $tradeRepo = new TradeRepo();

        $pager = $tradeRepo->paginate($params, $sort, $page, $limit);

        return $this->handleTrades($pager);
    }

    public function getTrade(int $id): TradeModel
    {
        $tradeRepo = new TradeRepo();

        return $tradeRepo->findById($id);
    }

    public function refundTrade(int $id): RefundModel
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
        $refund->channel = $trade->channel;
        $refund->subject = $trade->subject;
        $refund->owner_id = $trade->owner_id;
        $refund->order_id = $trade->order_id;
        $refund->trade_id = $trade->id;
        $refund->apply_note = $applyNote;

        $refund->create();

        return $refund;
    }

    public function getOrder(int $orderId): OrderModel
    {
        $orderRepo = new OrderRepo();

        return $orderRepo->findById($orderId);
    }

    public function getUser(int $userId): UserModel
    {
        $userRepo = new UserRepo();

        return $userRepo->findById($userId);
    }

    public function getAccount(int $userId): AccountModel
    {
        $accountRepo = new AccountRepo();

        return $accountRepo->findById($userId);
    }

    protected function findOrFail(int $id): TradeModel
    {
        $validator = new TradeValidator();

        return $validator->checkById($id);
    }

    protected function handleTrades(PagerRepoInterface $pager): PagerRepoInterface
    {
        if ($pager->getTotalItems() > 0) {

            $builder = new TradeListBuilder();

            $pipeA = $pager->getItems()->toArray();
            $pipeB = $builder->handleUsers($pipeA);
            $pipeC = $builder->handleOrders($pipeB);
            $pipeD = $builder->objects($pipeC);

            $pager->setItems($pipeD);
        }

        return $pager;
    }

}
