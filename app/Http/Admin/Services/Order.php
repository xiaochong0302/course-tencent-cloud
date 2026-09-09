<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Builders\OrderList as OrderListBuilder;
use App\Http\Admin\Services\Traits\AccountSearchTrait;
use App\Http\Admin\Services\Traits\OrderSearchTrait;
use App\Library\Paginator\Query as PaginateQuery;
use App\Models\Account as AccountModel;
use App\Models\Order as OrderModel;
use App\Models\User as UserModel;
use App\Repos\Account as AccountRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\User as UserRepo;
use App\Validators\Order as OrderValidator;
use Phalcon\Paginator\RepositoryInterface as PagerRepoInterface;

class Order extends Service
{

    use AccountSearchTrait;
    use OrderSearchTrait;

    public function getItemTypes(): array
    {
        return OrderModel::itemTypes();
    }

    public function getStatusTypes(): array
    {
        return OrderModel::statusTypes();
    }

    public function getChannelTypes(): array
    {
        return OrderModel::channelTypes();
    }

    public function getTrades(int $orderId)
    {
        $orderRepo = new OrderRepo();

        return $orderRepo->findTrades($orderId);
    }

    public function getRefunds(int $orderId)
    {
        $orderRepo = new OrderRepo();

        return $orderRepo->findRefunds($orderId);
    }

    public function getStatusHistory(int $orderId)
    {
        $orderRepo = new OrderRepo();

        return $orderRepo->findStatusHistory($orderId);
    }

    public function getOrders(): PagerRepoInterface
    {
        $pageQuery = new PaginateQuery();

        $params = $pageQuery->getParams();

        $params = $this->handleAccountSearchParams($params);
        $params = $this->handleOrderSearchParams($params);

        if (!empty($params['order_id'])) {
            $params['id'] = $params['order_id'];
        }

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pageQuery->getSort();
        $page = $pageQuery->getPage();
        $limit = $pageQuery->getLimit();

        $orderRepo = new OrderRepo();

        $pager = $orderRepo->paginate($params, $sort, $page, $limit);

        return $this->handleOrders($pager);
    }

    public function getOrder(int $id): OrderModel
    {
        return $this->findOrFail($id);
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

    protected function findOrFail(int $id): OrderModel
    {
        $validator = new OrderValidator();

        return $validator->checkById($id);
    }

    protected function handleOrders(PagerRepoInterface $pager): PagerRepoInterface
    {
        if ($pager->getTotalItems() > 0) {

            $builder = new OrderListBuilder();

            $pipeA = $pager->getItems()->toArray();
            $pipeB = $builder->handleItems($pipeA);
            $pipeC = $builder->handleUsers($pipeB);
            $pipeD = $builder->objects($pipeC);

            $pager->setItems($pipeD);
        }

        return $pager;
    }

}
