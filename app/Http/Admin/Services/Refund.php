<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Builders\RefundList as RefundListBuilder;
use App\Http\Admin\Services\Traits\AccountSearchTrait;
use App\Http\Admin\Services\Traits\OrderSearchTrait;
use App\Http\Admin\Services\Traits\RefundSearchTrait;
use App\Library\Paginator\Query as PaginateQuery;
use App\Models\Account as AccountModel;
use App\Models\Order as OrderModel;
use App\Models\Refund as RefundModel;
use App\Models\Task as TaskModel;
use App\Models\Trade as TradeModel;
use App\Models\User as UserModel;
use App\Repos\Account as AccountRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\Refund as RefundRepo;
use App\Repos\Trade as TradeRepo;
use App\Repos\User as UserRepo;
use App\Validators\Refund as RefundValidator;
use Phalcon\Paginator\RepositoryInterface as PagerRepoInterface;

class Refund extends Service
{

    use AccountSearchTrait;
    use OrderSearchTrait;
    use RefundSearchTrait;

    public function getStatusTypes(): array
    {
        return RefundModel::statusTypes();
    }

    public function getChannelTypes(): array
    {
        return OrderModel::channelTypes();
    }

    public function getRefunds(): PagerRepoInterface
    {
        $pageQuery = new PaginateQuery();

        $params = $pageQuery->getParams();

        $params = $this->handleAccountSearchParams($params);
        $params = $this->handleOrderSearchParams($params);
        $params = $this->handleRefundSearchParams($params);

        if (!empty($params['refund_id'])) {
            $params['id'] = $params['refund_id'];
        }

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pageQuery->getSort();
        $page = $pageQuery->getPage();
        $limit = $pageQuery->getLimit();

        $refundRepo = new RefundRepo();

        $pager = $refundRepo->paginate($params, $sort, $page, $limit);

        return $this->handleRefunds($pager);
    }

    public function getRefund(int $id): RefundModel
    {
        return $this->findOrFail($id);
    }

    public function reviewRefund(int $id): RefundModel
    {
        $refund = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new RefundValidator();

        $validator->checkIfAllowReview($refund);

        $refund->status = $validator->checkReviewStatus($post['review_status']);
        $refund->review_note = $validator->checkReviewNote($post['review_note']);

        try {

            $this->db->begin();

            $refund->update();

            if ($refund->status == RefundModel::STATUS_APPROVED) {

                $task = new TaskModel();

                $task->item_id = $refund->id;
                $task->item_type = TaskModel::TYPE_REFUND_APPLY;
                $task->priority = TaskModel::PRIORITY_HIGH;
                $task->status = TaskModel::STATUS_PENDING;

                $task->create();
            }

            $this->db->commit();

        } catch (\Exception $e) {

            $this->db->rollback();

            $logger = $this->getLogger('refund');

            $logger->error('Refund Review Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                    'refund' => $refund,
                ]));

            throw new \RuntimeException('sys.rollback');
        }

        return $refund;
    }

    public function getStatusHistory(int $id)
    {
        $refundRepo = new RefundRepo();

        return $refundRepo->findStatusHistory($id);
    }

    public function getTrade(int $tradeId): TradeModel
    {
        $tradeRepo = new TradeRepo();

        return $tradeRepo->findById($tradeId);
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

    protected function findOrFail(int $id): RefundModel
    {
        $validator = new RefundValidator();

        return $validator->checkById($id);
    }

    protected function handleRefunds(PagerRepoInterface $pager): PagerRepoInterface
    {
        if ($pager->getTotalItems() > 0) {

            $builder = new RefundListBuilder();

            $pipeA = $pager->getItems()->toArray();
            $pipeB = $builder->handleUsers($pipeA);
            $pipeC = $builder->handleOrders($pipeB);
            $pipeD = $builder->objects($pipeC);

            $pager->setItems($pipeD);
        }

        return $pager;
    }

}
