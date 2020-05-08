<?php

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
use App\Services\Pay\Alipay as AlipayService;
use App\Services\Pay\Wxpay as WxpayService;
use App\Services\Smser\Refund as RefundSmser;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class RefundTask extends Task
{

    /**
     * 重试次数
     */
    const TRY_COUNT = 3;

    public function mainAction()
    {
        $logger = $this->getLogger('refund');

        $tasks = $this->findTasks();

        if ($tasks->count() == 0) {
            return;
        }

        $tradeRepo = new TradeRepo();
        $orderRepo = new OrderRepo();
        $refundRepo = new RefundRepo();

        foreach ($tasks as $task) {

            /**
             * @var array $itemInfo
             */
            $itemInfo = $task->item_info;

            $refund = $refundRepo->findById($itemInfo['refund']['id']);
            $trade = $tradeRepo->findById($itemInfo['refund']['trade_id']);
            $order = $orderRepo->findById($itemInfo['refund']['order_id']);

            if (!$refund || !$trade || !$order) {
                continue;
            }

            try {

                $this->db->begin();

                $this->handleTradeRefund($trade, $refund);

                $this->handleOrderRefund($order);

                $refund->status = RefundModel::STATUS_FINISHED;

                if ($refund->update() === false) {
                    throw new \RuntimeException('Update Refund Status Failed');
                }

                $trade->status = TradeModel::STATUS_REFUNDED;

                if ($trade->update() === false) {
                    throw new \RuntimeException('Update Trade Status Failed');
                }

                $order->status = OrderModel::STATUS_REFUNDED;

                if ($order->update() === false) {
                    throw new \RuntimeException('Update Order Status Failed');
                }

                $task->status = TaskModel::STATUS_FINISHED;

                if ($task->update() === false) {
                    throw new \RuntimeException('Update Task Status Failed');
                }

                $this->db->commit();

                $this->handleRefundNotice($refund);

            } catch (\Exception $e) {

                $this->db->rollback();

                $task->try_count += 1;
                $task->priority += 1;

                if ($task->try_count > self::TRY_COUNT) {
                    $task->status = TaskModel::STATUS_FAILED;
                }

                $task->update();

                $logger->info('Refund Task Exception ' . kg_json_encode([
                        'message' => $e->getMessage(),
                        'task' => $task->toArray(),
                    ]));
            }

            if ($task->status == TaskModel::STATUS_FAILED) {
                $refund->status = RefundModel::STATUS_FAILED;
                $refund->update();
            }
        }
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
            throw new \RuntimeException('Pay Refund Failed');
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

        $courseUser = $courseUserRepo->findCourseStudent($order->item_id, $order->user_id);

        if ($courseUser) {

            $courseUser->deleted = 1;

            if ($courseUser->update() === false) {
                throw new \RuntimeException('Delete CourseQuery User Failed');
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

        /**
         * @var array $itemInfo
         */
        $itemInfo = $order->item_info;

        foreach ($itemInfo['courses'] as $course) {

            $courseUser = $courseUserRepo->findCourseStudent($course['id'], $order->user_id);

            if ($courseUser) {

                $courseUser->deleted = 1;

                if ($courseUser->update() === false) {
                    throw new \RuntimeException('Delete CourseQuery User Failed');
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

        $user = $userRepo->findById($order->user_id);

        /**
         * @var array $itemInfo
         */
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
    protected function handleRefundNotice(RefundModel $refund)
    {
        $smser = new RefundSmser();

        $smser->handle($refund);
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|TaskModel[]
     */
    protected function findTasks($limit = 5)
    {
        $itemType = TaskModel::TYPE_REFUND;
        $status = TaskModel::STATUS_PENDING;
        $tryCount = self::TRY_COUNT;

        return TaskModel::query()
            ->where('item_type = :item_type:', ['item_type' => $itemType])
            ->andWhere('status = :status:', ['status' => $status])
            ->andWhere('try_count < :try_count:', ['try_count' => $tryCount])
            ->orderBy('priority ASC')
            ->limit($limit)
            ->execute();
    }

}
