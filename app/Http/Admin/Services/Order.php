<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Builders\OrderList as OrderListBuilder;
use App\Library\Paginator\Query as PaginateQuery;
use App\Library\Validators\Common as CommonValidator;
use App\Models\Order as OrderModel;
use App\Repos\Account as AccountRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\User as UserRepo;
use App\Validators\Order as OrderValidator;

class Order extends Service
{

    public function getItemTypes()
    {
        return OrderModel::itemTypes();
    }

    public function getStatusTypes()
    {
        return OrderModel::statusTypes();
    }

    public function getOrders()
    {
        $pageQuery = new PaginateQuery();

        $params = $pageQuery->getParams();

        $params['deleted'] = $params['deleted'] ?? 0;

        /**
         * 兼容订单编号或订单序号查询
         */
        if (isset($params['id']) && strlen($params['id']) > 10) {
            $orderRepo = new OrderRepo();
            $order = $orderRepo->findBySn($params['id']);
            $params['id'] = $order ? $order->id : -1000;
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

        $orderRepo = new OrderRepo();

        $pager = $orderRepo->paginate($params, $sort, $page, $limit);

        return $this->handleOrders($pager);
    }

    public function getTrades($sn)
    {
        $orderRepo = new OrderRepo();

        return $orderRepo->findTrades($sn);
    }

    public function getRefunds($sn)
    {
        $orderRepo = new OrderRepo();

        return $orderRepo->findRefunds($sn);
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

    public function getOrder($id)
    {
        return $this->findOrFail($id);
    }

    public function getStatusHistory($id)
    {
        $orderRepo = new OrderRepo();

        return $orderRepo->findStatusHistory($id);
    }

    protected function findOrFail($id)
    {
        $validator = new OrderValidator();

        return $validator->checkOrderById($id);
    }

    protected function handleOrders($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new OrderListBuilder();

            $pipeA = $pager->items->toArray();
            $pipeB = $builder->handleItems($pipeA);
            $pipeC = $builder->handleUsers($pipeB);
            $pipeD = $builder->objects($pipeC);

            $pager->items = $pipeD;
        }

        return $pager;
    }

}
