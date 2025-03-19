<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Models\Order as OrderModel;
use App\Models\Refund as RefundModel;
use App\Models\Task as TaskModel;
use App\Models\Trade as TradeModel;
use App\Repos\Course as CourseRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\Package as PackageRepo;
use App\Repos\User as UserRepo;
use App\Repos\Vip as VipRepo;
use App\Services\Logic\Deliver\CourseDeliver as CourseDeliverService;
use App\Services\Logic\Deliver\PackageDeliver as PackageDeliverService;
use App\Services\Logic\Deliver\VipDeliver as VipDeliverService;
use App\Services\Logic\Notice\External\OrderFinish as OrderFinishNotice;
use App\Services\Logic\Point\History\OrderConsume as OrderConsumePointHistory;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class DeliverTask extends Task
{

    public function mainAction()
    {
        $tasks = $this->findTasks(30);

        echo sprintf('pending tasks: %s', $tasks->count()) . PHP_EOL;

        if ($tasks->count() == 0) return;

        echo '------ start deliver task ------' . PHP_EOL;

        $orderRepo = new OrderRepo();

        foreach ($tasks as $task) {

            $order = $orderRepo->findById($task->item_id);

            try {

                $this->db->begin();

                switch ($order->item_type) {
                    case OrderModel::ITEM_COURSE:
                        $this->handleCourseOrder($order);
                        break;
                    case OrderModel::ITEM_PACKAGE:
                        $this->handlePackageOrder($order);
                        break;
                    case OrderModel::ITEM_VIP:
                        $this->handleVipOrder($order);
                        break;
                }

                $order->status = OrderModel::STATUS_FINISHED;
                $order->update();

                $task->status = TaskModel::STATUS_FINISHED;
                $task->update();

                $this->db->commit();

            } catch (\Exception $e) {

                $this->db->rollback();

                $task->try_count += 1;
                $task->priority += 1;

                if ($task->try_count > $task->max_try_count) {
                    $task->status = TaskModel::STATUS_FAILED;
                }

                $task->update();

                $logger = $this->getLogger('deliver');

                $logger->error('Deliver Task Exception ' . kg_json_encode([
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'message' => $e->getMessage(),
                        'task' => $task->toArray(),
                    ]));
            }

            if ($task->status == TaskModel::STATUS_FINISHED) {
                $this->handleOrderConsumePoint($order);
                $this->handleOrderFinishNotice($order);
            } elseif ($task->status == TaskModel::STATUS_FAILED) {
                $this->handleOrderRefund($order);
            }
        }

        echo '------ end deliver task ------' . PHP_EOL;
    }

    protected function handleCourseOrder(OrderModel $order)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($order->item_id);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($order->owner_id);

        $service = new CourseDeliverService();

        $service->handle($course, $user);
    }

    protected function handlePackageOrder(OrderModel $order)
    {
        $packageRepo = new PackageRepo();

        $package = $packageRepo->findById($order->item_id);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($order->owner_id);

        $service = new PackageDeliverService();

        $service->handle($package, $user);
    }

    protected function handleVipOrder(OrderModel $order)
    {
        $vipRepo = new VipRepo();

        $vip = $vipRepo->findById($order->item_id);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($order->owner_id);

        $service = new VipDeliverService();

        $service->handle($vip, $user);

        /**
         * 先下单购买课程，发现会员有优惠，于是购买会员，再回头购买课程
         * 自动关闭未支付订单，让用户可以使用会员价再次下单
         */
        $this->closePendingOrders($user->id);
    }

    protected function closePendingOrders($userId)
    {
        $orders = $this->findUserPendingOrders($userId);

        if ($orders->count() == 0) return;

        $itemTypes = [
            OrderModel::ITEM_COURSE,
            OrderModel::ITEM_PACKAGE,
        ];

        foreach ($orders as $order) {
            if (in_array($order->item_type, $itemTypes)) {
                $order->status = OrderModel::STATUS_CLOSED;
                $order->update();
            }
        }
    }

    protected function handleOrderConsumePoint(OrderModel $order)
    {
        $service = new OrderConsumePointHistory();

        $service->handle($order);
    }

    protected function handleOrderFinishNotice(OrderModel $order)
    {
        $notice = new OrderFinishNotice();

        $notice->createTask($order);
    }

    protected function handleOrderRefund(OrderModel $order)
    {
        $trade = $this->findFinishedTrade($order->id);

        if (!$trade) return;

        $refund = new RefundModel();

        $refund->owner_id = $order->owner_id;
        $refund->order_id = $order->id;
        $refund->trade_id = $trade->id;
        $refund->subject = $order->subject;
        $refund->amount = $order->amount;
        $refund->apply_note = '开通服务失败，自动退款';
        $refund->review_note = '自动操作';

        $refund->create();

        $task = new TaskModel();

        $itemInfo = [
            'refund' => ['id' => $refund->id],
        ];

        $task->item_id = $refund->id;
        $task->item_info = $itemInfo;
        $task->item_type = TaskModel::TYPE_REFUND;
        $task->priority = TaskModel::PRIORITY_HIGH;
        $task->status = TaskModel::STATUS_PENDING;

        $task->create();
    }

    /**
     * @param int $orderId
     * @return Model|TradeModel
     */
    protected function findFinishedTrade($orderId)
    {
        $status = TradeModel::STATUS_FINISHED;

        return TradeModel::findFirst([
            'conditions' => ['order_id = :order_id: AND status = :status:'],
            'bind' => ['order_id' => $orderId, 'status' => $status],
            'order' => 'id DESC',
        ]);
    }

    /**
     * @param int $userId
     * @return ResultsetInterface|Resultset|OrderModel[]
     */
    protected function findUserPendingOrders($userId)
    {
        $status = OrderModel::STATUS_PENDING;

        return OrderModel::query()
            ->where('owner_id = :owner_id:', ['owner_id' => $userId])
            ->andWhere('status = :status:', ['status' => $status])
            ->execute();
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|TaskModel[]
     */
    protected function findTasks($limit = 100)
    {
        $itemType = TaskModel::TYPE_DELIVER;
        $status = TaskModel::STATUS_PENDING;
        $createTime = strtotime('-7 days');

        return TaskModel::query()
            ->where('item_type = :item_type:', ['item_type' => $itemType])
            ->andWhere('status = :status:', ['status' => $status])
            ->andWhere('create_time > :create_time:', ['create_time' => $createTime])
            ->orderBy('priority ASC')
            ->limit($limit)
            ->execute();
    }

}
