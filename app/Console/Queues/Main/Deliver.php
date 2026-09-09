<?php
/**
 * @copyright Copyright (c) 2023 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Queues\Main;

use App\Models\KgProduct as KgProductModel;
use App\Models\Order as OrderModel;
use App\Models\Refund as RefundModel;
use App\Models\Task as TaskModel;
use App\Repos\Course as CourseRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\User as UserRepo;
use App\Repos\Vip as VipRepo;
use App\Services\Logic\Deliver\CourseDeliver as CourseDeliverService;
use App\Services\Logic\Deliver\VipDeliver as VipDeliverService;
use App\Services\Logic\OrderTrait;
use App\Traits\Service as ServiceTrait;
use Phalcon\Di\Injectable;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Deliver extends Injectable
{

    use ServiceTrait;
    use OrderTrait;

    public function handle(TaskModel $task): void
    {
        echo '------ start deliver task ------' . PHP_EOL;

        $orderRepo = new OrderRepo();

        $order = $orderRepo->findById($task->item_id);

        if (!$order) {
            throw new \RuntimeException("Deliver Task, Order:{$task->item_id} Not Found");
        }

        $logger = $this->getLogger('deliver');

        if ($order->status == OrderModel::STATUS_FINISHED) {
            $logger->warning("Order:{$order->id} already delivered, skip duplicate processing");
            return;
        }

        try {

            $this->db->begin();

            switch ($order->item_type) {
                case KgProductModel::ITEM_COURSE:
                    $this->handleCourseOrder($order);
                    break;
                case KgProductModel::ITEM_VIP:
                    $this->handleVipOrder($order);
                    break;
            }

            $order->status = OrderModel::STATUS_FINISHED;
            $order->update();

            $this->db->commit();

        } catch (\Exception $e) {

            $this->db->rollback();

            $this->handleOrderRefund($order);

            $logger->error('Deliver Task Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                    'task' => $task->toArray(),
                ]));

            throw $e;
        }

        echo '------ end deliver task ------' . PHP_EOL;
    }

    protected function handleCourseOrder(OrderModel $order): void
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($order->item_id);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($order->owner_id);

        $service = new CourseDeliverService();

        $service->handle($course, $user);
    }

    protected function handleVipOrder(OrderModel $order): void
    {
        $vipRepo = new VipRepo();

        $vip = $vipRepo->findById($order->item_id);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($order->owner_id);

        $service = new VipDeliverService();

        $service->handle($vip, $user);

        /**
         * 先下单购买商品，发现会员有优惠，于是购买会员，再回头购买商品
         * 自动关闭未支付订单，让用户可以使用会员价再次下单
         */
        $this->closePendingOrders($user->id);
    }

    protected function closePendingOrders(int $userId): void
    {
        $orders = $this->findUserPendingOrders($userId);

        if ($orders->count() == 0) return;

        $itemTypes = [
            KgProductModel::ITEM_COURSE,
        ];

        foreach ($orders as $order) {
            $case1 = in_array($order->item_type, $itemTypes);
            $case2 = $order->promotion_type == 0;
            if ($case1 && $case2) {
                $order->status = OrderModel::STATUS_CLOSED;
                $order->update();
            }
        }
    }

    protected function handleOrderRefund(OrderModel $order): void
    {
        $orderRepo = new OrderRepo();

        $trade = $orderRepo->findFinishedTrade($order->id);

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

        $task->item_id = $refund->id;
        $task->item_type = TaskModel::TYPE_REFUND_APPLY;
        $task->priority = TaskModel::PRIORITY_HIGH;
        $task->status = TaskModel::STATUS_PENDING;

        $task->create();
    }

    /**
     * @param int $userId
     * @return ResultsetInterface|Resultset|OrderModel[]
     */
    protected function findUserPendingOrders(int $userId)
    {
        $status = OrderModel::STATUS_PENDING;

        return OrderModel::query()
            ->where('owner_id = :owner_id:', ['owner_id' => $userId])
            ->andWhere('status = :status:', ['status' => $status])
            ->execute();
    }

}
