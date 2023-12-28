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
use App\Library\Paginator\Query as PaginateQuery;
use App\Models\Refund as RefundModel;
use App\Models\Task as TaskModel;
use App\Repos\Account as AccountRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\Refund as RefundRepo;
use App\Repos\Trade as TradeRepo;
use App\Repos\User as UserRepo;
use App\Validators\Refund as RefundValidator;

class Refund extends Service
{

    use AccountSearchTrait;
    use OrderSearchTrait;

    public function getStatusTypes()
    {
        return RefundModel::statusTypes();
    }

    public function getRefunds()
    {
        $pageQuery = new PaginateQuery();

        $params = $pageQuery->getParams();

        $params = $this->handleAccountSearchParams($params);
        $params = $this->handleOrderSearchParams($params);

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pageQuery->getSort();
        $page = $pageQuery->getPage();
        $limit = $pageQuery->getLimit();

        $refundRepo = new RefundRepo();

        $pager = $refundRepo->paginate($params, $sort, $page, $limit);

        return $this->handleRefunds($pager);
    }

    public function getRefund($id)
    {
        return $this->findOrFail($id);
    }

    public function getStatusHistory($id)
    {
        $refundRepo = new RefundRepo();

        return $refundRepo->findStatusHistory($id);
    }

    public function getTrade($tradeId)
    {
        $tradeRepo = new TradeRepo();

        return $tradeRepo->findById($tradeId);
    }

    public function getOrder($orderId)
    {
        $orderRepo = new OrderRepo();

        return $orderRepo->findById($orderId);
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

    public function reviewRefund($id)
    {
        $refund = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new RefundValidator();

        $data = [];

        $validator->checkIfAllowReview($refund);

        $data['status'] = $validator->checkReviewStatus($post['review_status']);
        $data['review_note'] = $validator->checkReviewNote($post['review_note']);

        $refund->update($data);

        if ($refund->status == RefundModel::STATUS_APPROVED) {

            $task = new TaskModel();

            $itemInfo = [
                'refund' => ['id' => $refund->id],
            ];

            $task->item_id = $refund->id;
            $task->item_type = TaskModel::TYPE_REFUND;
            $task->item_info = $itemInfo;
            $task->priority = TaskModel::PRIORITY_HIGH;
            $task->status = TaskModel::STATUS_PENDING;

            $task->create();
        }

        return $refund;
    }

    protected function findOrFail($id)
    {
        $validator = new RefundValidator();

        return $validator->checkRefund($id);
    }

    protected function handleRefunds($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new RefundListBuilder();

            $pipeA = $pager->items->toArray();
            $pipeB = $builder->handleUsers($pipeA);
            $pipeC = $builder->handleOrders($pipeB);
            $pipeD = $builder->objects($pipeC);

            $pager->items = $pipeD;
        }

        return $pager;
    }

}
