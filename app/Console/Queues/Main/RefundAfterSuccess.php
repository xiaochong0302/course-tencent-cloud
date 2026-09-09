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
use App\Models\Trade as TradeModel;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\Refund as RefundRepo;
use App\Repos\Trade as TradeRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\OrderTrait;
use App\Traits\Service as ServiceTrait;
use Phalcon\Di\Injectable;

class RefundAfterSuccess extends Injectable
{

    use ServiceTrait;
    use OrderTrait;

    public function handle(TaskModel $task): void
    {
        echo '------ start refund after success task ------' . PHP_EOL;

        $refundRepo = new RefundRepo();

        $refund = $refundRepo->findById($task->item_id);

        if (!$refund) {
            throw new \RuntimeException("Refund After Success Task, Refund:{$task->item_id} Not Found");
        }

        $logger = $this->getLogger('refund');

        if ($refund->status == RefundModel::STATUS_FINISHED) {
            $logger->warning("Refund:{$refund->id} already finished, skip duplicate processing");
            return;
        }

        $tradeRepo = new TradeRepo();

        $trade = $tradeRepo->findById($refund->trade_id);

        $orderRepo = new OrderRepo();

        $order = $orderRepo->findById($refund->order_id);

        try {

            $this->db->begin();

            $this->handleOrderRefund($order);

            $refund->status = RefundModel::STATUS_FINISHED;
            $refund->update();

            $trade->status = TradeModel::STATUS_REFUNDED;
            $trade->update();

            $order->status = OrderModel::STATUS_REFUNDED;
            $order->update();

            $this->db->commit();

        } catch (\Exception $e) {

            $this->db->rollback();

            $refund->status = RefundModel::STATUS_FAILED;
            $refund->update();

            $logger->error('Refund After Success Task Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                    'task' => $task->toArray(),
                ]));

            throw $e;
        }

        echo '------ end refund after success task ------' . PHP_EOL;
    }

    /**
     * 处理订单退款
     */
    protected function handleOrderRefund(OrderModel $order): void
    {
        switch ($order->item_type) {
            case KgProductModel::ITEM_COURSE:
                $this->handleCourseOrderRefund($order);
                break;
            case KgProductModel::ITEM_VIP:
                $this->handleVipOrderRefund($order);
                break;
        }
    }

    /**
     * 处理课程订单退款
     */
    protected function handleCourseOrderRefund(OrderModel $order): void
    {
        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->findCourseUser($order->item_id, $order->owner_id);

        if ($courseUser) {
            $courseUser->deleted = 1;
            $courseUser->update();
        }
    }

    /**
     * 处理会员订单退款
     */
    protected function handleVipOrderRefund(OrderModel $order): void
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($order->owner_id);

        $itemInfo = $order->item_info;

        $diffTime = "-{$itemInfo['vip']['expiry']} months";
        $baseTime = $itemInfo['vip']['expiry_time'];

        $user->vip_expiry_time = strtotime($diffTime, $baseTime);

        if ($user->vip_expiry_time < time()) {
            $user->vip = 0;
        }

        $user->update();
    }

}
