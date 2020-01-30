<?php

namespace App\Http\Admin\Services;

use App\Builders\RefundList as RefundListBuilder;
use App\Library\Paginator\Query as PaginateQuery;
use App\Models\Task as TaskModel;
use App\Repos\Account as AccountRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\Refund as RefundRepo;
use App\Repos\Trade as TradeRepo;
use App\Repos\User as UserRepo;
use App\Validators\Refund as RefundValidator;

class Refund extends Service
{

    public function getRefunds()
    {
        $pageQuery = new PaginateQuery();

        $params = $pageQuery->getParams();
        $sort = $pageQuery->getSort();
        $page = $pageQuery->getPage();
        $limit = $pageQuery->getLimit();

        $refundRepo = new RefundRepo();

        $pager = $refundRepo->paginate($params, $sort, $page, $limit);

        return $this->handleRefunds($pager);
    }

    public function getRefund($id)
    {
        $refund = $this->findOrFail($id);

        return $refund;
    }

    public function getTrade($sn)
    {
        $tradeRepo = new TradeRepo();

        $trade = $tradeRepo->findBySn($sn);

        return $trade;
    }

    public function getOrder($sn)
    {
        $orderRepo = new OrderRepo();

        $order = $orderRepo->findBySn($sn);

        return $order;
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

    public function reviewRefund($id)
    {
        $refund = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new RefundValidator();

        $data = [];

        $validator->checkIfAllowReview($refund);

        $data['status'] = $validator->checkReviewStatus($post['status']);
        $data['review_note'] = $validator->checkReviewNote($post['review_note']);

        $refund->update($data);

        $task = new TaskModel();

        $task->item_id = $refund->id;
        $task->item_type = TaskModel::TYPE_REFUND;
        $task->item_info = ['refund' => $refund->toArray()];
        $task->priority = TaskModel::PRIORITY_HIGH;
        $task->status = TaskModel::STATUS_PENDING;

        $task->create();

        return $refund;
    }

    protected function findOrFail($id)
    {
        $validator = new RefundValidator();

        $result = $validator->checkRefund($id);

        return $result;
    }

    protected function handleRefunds($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new RefundListBuilder();

            $pipeA = $pager->items->toArray();
            $pipeB = $builder->handleUsers($pipeA);
            $pipeC = $builder->arrayToObject($pipeB);

            $pager->items = $pipeC;
        }

        return $pager;
    }

}
