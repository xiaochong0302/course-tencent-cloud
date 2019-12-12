<?php

namespace App\Http\Admin\Services;

use App\Library\Paginator\Query as PaginateQuery;
use App\Models\Order as OrderModel;
use App\Repos\Order as OrderRepo;
use App\Repos\User as UserRepo;
use App\Transformers\OrderList as OrderListTransformer;
use App\Validators\Order as OrderValidator;

class Order extends Service
{

    public function getOrders()
    {
        $pageQuery = new PaginateQuery();

        $params = $pageQuery->getParams();
        $sort = $pageQuery->getSort();
        $page = $pageQuery->getPage();
        $limit = $pageQuery->getLimit();

        $orderRepo = new OrderRepo();

        $pager = $orderRepo->paginate($params, $sort, $page, $limit);

        return $this->handleOrders($pager);
    }

    public function getTrades($sn)
    {
        $orderRepo = new OrderRepo();

        $trades = $orderRepo->findTrades($sn);

        return $trades;
    }

    public function getRefunds($sn)
    {
        $orderRepo = new OrderRepo();

        $trades = $orderRepo->findRefunds($sn);

        return $trades;
    }

    public function getUser($userId)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($userId);

        return $user;
    }

    public function getOrder($id)
    {
        $order = $this->findOrFail($id);

        return $order;
    }

    public function closeOrder($id)
    {
        $order = $this->findOrFail($id);

        if ($order->status == OrderModel::STATUS_PENDING) {
            $order->status = OrderModel::STATUS_CLOSED;
            $order->update();
        }

        return $order;
    }

    protected function findOrFail($id)
    {
        $validator = new OrderValidator();

        $result = $validator->checkOrder($id);

        return $result;
    }

    protected function handleOrders($pager)
    {
        if ($pager->total_items > 0) {

            $transformer = new OrderListTransformer();

            $pipeA = $pager->items->toArray();
            $pipeB = $transformer->handleItems($pipeA);
            $pipeC = $transformer->handleUsers($pipeB);
            $pipeD = $transformer->arrayToObject($pipeC);

            $pager->items = $pipeD;
        }

        return $pager;
    }

}
