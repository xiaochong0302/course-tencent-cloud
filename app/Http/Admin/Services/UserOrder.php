<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Builders\OrderList as OrderListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\User as UserModel;
use App\Repos\Order as OrderRepo;
use App\Validators\User as UserValidator;
use Phalcon\Paginator\RepositoryInterface as PagerRepoInterface;

class UserOrder extends Service
{

    public function getOrders($userId): PagerRepoInterface
    {
        $user = $this->findUserOrFail($userId);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['owner_id'] = $user->id;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $orderRepo = new OrderRepo();

        $pager = $orderRepo->paginate($params, $sort, $page, $limit);

        return $this->handleOrders($pager);
    }

    protected function handleOrders(PagerRepoInterface $pager): PagerRepoInterface
    {
        if ($pager->getTotalItems() > 0) {

            $builder = new OrderListBuilder();

            $pipeA = $pager->getItems()->toArray();
            $pipeB = $builder->handleItems($pipeA);
            $pipeC = $builder->objects($pipeB);

            $pager->setItems($pipeC);
        }

        return $pager;
    }

    protected function findUserOrFail(int $id): UserModel
    {
        $validator = new UserValidator();

        return $validator->checkUser($id);
    }

}
