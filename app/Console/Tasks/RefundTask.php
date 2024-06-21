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
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\Refund as RefundRepo;
use App\Repos\Trade as TradeRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\Notice\External\RefundFinish as RefundFinishNotice;
use App\Services\Pay\Alipay as AlipayService;
use App\Services\Pay\Wxpay as WxpayService;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class RefundTask extends Task
{

    public function mainAction()
    {
        $tasks = $this->findTasks(30);

        echo sprintf('pending tasks: %s', $tasks->count()) . PHP_EOL;

        if ($tasks->count() == 0) return;

        echo '------ start refund task ------' . PHP_EOL;

        $tradeRepo = new TradeRepo();
        $orderRepo = new OrderRepo();
        $refundRepo = new RefundRepo();

        foreach ($tasks as $task) {

            $refund = $refundRepo->findById($task->item_id);
            $trade = $tradeRepo->findById($refund->trade_id);
            $order = $orderRepo->findById($refund->order_id);

            if ($refund->status != RefundModel::STATUS_APPROVED) {
                $task->status = TaskModel::STATUS_CANCELED;
                $task->update();
                continue;
            }

            try {

                $this->db->begin();

                $this->handleTradeRefund($trade, $refund);
                $this->handleOrderRefund($order);

                $refund->status = RefundModel::STATUS_FINISHED;
                $refund->update();

                $trade->status = TradeModel::STATUS_REFUNDED;
                $trade->update();

                $order->status = OrderModel::STATUS_REFUNDED;
                $order->update();

                $task->status = TaskModel::STATUS_FINISHED;
                $task->update();

                $this->db->commit();

                $this->handleRefundFinishNotice($refund);

            } catch (\Exception $e) {

                $this->db->rollback();

                $task->try_count += 1;
                $task->priority += 1;

                if ($task->try_count > $task->max_try_count) {
                    $task->status = TaskModel::STATUS_FAILED;
                }

                $task->update();

                $logger = $this->getLogger('refund');

                $logger->error('Refund Task Exception ' . kg_json_encode([
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'message' => $e->getMessage(),
                        'task' => $task->toArray(),
                    ]));
            }

            if ($task->status == TaskModel::STATUS_FAILED) {
                $refund->status = RefundModel::STATUS_FAILED;
                $refund->update();
            }
        }

        echo '------ end refund task ------' . PHP_EOL;
    }

    /**
     * 处理交易退款
     *
     * @param TradeModel $trade
     * @param RefundModel $refund
     */
    protected function handleTradeRefund(TradeModel $trade, RefundModel $refund)
    {
        $response = false;

        if ($trade->channel == TradeModel::CHANNEL_ALIPAY) {

            $alipay = new AlipayService();

            $response = $alipay->refund($refund);

        } elseif ($trade->channel == TradeModel::CHANNEL_WXPAY) {

            $wxpay = new WxpayService();

            $response = $wxpay->refund($refund);
        }

        if (!$response) {
            throw new \RuntimeException('Trade Refund Failed');
        }
    }

    /**
     * 处理订单退款
     *
     * @param OrderModel $order
     */
    protected function handleOrderRefund(OrderModel $order)
    {
        switch ($order->item_type) {
            case OrderModel::ITEM_COURSE:
                $this->handleCourseOrderRefund($order);
                break;
            case OrderModel::ITEM_PACKAGE:
                $this->handlePackageOrderRefund($order);
                break;
            case OrderModel::ITEM_VIP:
                $this->handleVipOrderRefund($order);
                break;
            case OrderModel::ITEM_TEST:
                $this->handleTestOrderRefund($order);
                break;
        }
    }

    /**
     * 处理课程订单退款
     *
     * @param OrderModel $order
     */
    protected function handleCourseOrderRefund(OrderModel $order)
    {
        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->findCourseUser($order->item_id, $order->owner_id);

        if ($courseUser) {
            $courseUser->deleted = 1;
            if ($courseUser->update() === false) {
                throw new \RuntimeException('Delete Course User Failed');
            }
        }
    }

    /**
     * 处理套餐订单退款
     *
     * @param OrderModel $order
     */
    protected function handlePackageOrderRefund(OrderModel $order)
    {
        $courseUserRepo = new CourseUserRepo();

        $itemInfo = $order->item_info;

        foreach ($itemInfo['courses'] as $course) {

            $courseUser = $courseUserRepo->findCourseUser($course['id'], $order->owner_id);

            if ($courseUser) {
                $courseUser->deleted = 1;
                if ($courseUser->update() === false) {
                    throw new \RuntimeException('Delete Course User Failed');
                }
            }
        }
    }

    /**
     * 处理会员订单退款
     *
     * @param OrderModel $order
     */
    protected function handleVipOrderRefund(OrderModel $order)
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

        if ($user->update() === false) {
            throw new \RuntimeException('Update User Vip Failed');
        }
    }

    /**
     * 处理测试订单退款
     *
     * @param OrderModel $order
     */
    protected function handleTestOrderRefund(OrderModel $order)
    {

    }

    /**
     * @param RefundModel $refund
     */
    protected function handleRefundFinishNotice(RefundModel $refund)
    {
        $notice = new RefundFinishNotice();

        $notice->createTask($refund);
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|TaskModel[]
     */
    protected function findTasks($limit = 30)
    {
        $itemType = TaskModel::TYPE_REFUND;
        $status = TaskModel::STATUS_PENDING;
        $createTime = strtotime('-3 days');

        return TaskModel::query()
            ->where('item_type = :item_type:', ['item_type' => $itemType])
            ->andWhere('status = :status:', ['status' => $status])
            ->andWhere('create_time > :create_time:', ['create_time' => $createTime])
            ->orderBy('priority ASC')
            ->limit($limit)
            ->execute();
    }

}
