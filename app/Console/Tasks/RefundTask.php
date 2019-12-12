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
use App\Services\Alipay as AlipayService;
use App\Services\Wxpay as WxpayService;

class RefundTask extends Task
{

    const TRY_COUNT = 5;

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

            $refund = $refundRepo->findBySn($task->item_info['refund']['sn']);
            $trade = $tradeRepo->findBySn($task->item_info['refund']['trade_sn']);
            $order = $orderRepo->findBySn($task->item_info['refund']['order_sn']);

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

            } catch (\Exception $e) {

                $this->db->rollback();

                $task->try_count += 1;

                if ($task->try_count > self::TRY_COUNT) {
                    $task->status = TaskModel::STATUS_FAILED;
                    $refund->status = RefundModel::STATUS_FAILED;
                    $refund->update();
                }

                $task->update();

                $logger->info('Refund Task Exception ' . kg_json_encode([
                        'message' => $e->getMessage(),
                        'task' => $task->toArray(),
                    ]));
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
            $response = $alipay->refundOrder([
                'out_trade_no' => $trade->sn,
                'out_request_no' => $refund->sn,
                'refund_amount' => $refund->amount,
            ]);
        } elseif ($trade->channel == TradeModel::CHANNEL_WXPAY) {
            $wxpay = new WxpayService();
            $response = $wxpay->refundOrder([
                'out_trade_no' => $trade->sn,
                'out_refund_no' => $refund->sn,
                'total_fee' => 100 * $trade->order_amount,
                'refund_fee' => 100 * $refund->amount,
            ]);
        }

        if (!$response) {
            throw new \RuntimeException('Payment Refund Failed');
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
            case OrderModel::TYPE_COURSE:
                $this->handleCourseOrderRefund($order);
                break;
            case OrderModel::TYPE_PACKAGE:
                $this->handlePackageOrderRefund($order);
                break;
            case OrderModel::TYPE_REWARD:
                $this->handleRewardOrderRefund($order);
                break;
            case OrderModel::TYPE_VIP:
                $this->handleVipOrderRefund($order);
                break;
            case OrderModel::TYPE_TEST:
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

        foreach ($order->item_info['courses'] as $course) {
            $courseUser = $courseUserRepo->findCourseStudent($course['id'], $order->user_id);
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

        $user = $userRepo->findById($order->user_id);

        $baseTime = $user->vip_expiry;

        switch ($order->item_info['vip']['duration']) {
            case 'one_month':
                $user->vip_expiry = strtotime('-1 months', $baseTime);
                break;
            case 'three_month':
                $user->vip_expiry = strtotime('-3 months', $baseTime);
                break;
            case 'six_month':
                $user->vip_expiry = strtotime('-6 months', $baseTime);
                break;
            case 'twelve_month':
                $user->vip_expiry = strtotime('-12 months', $baseTime);
                break;
        }

        if ($user->vip_expiry < time()) {
            $user->vip = 0;
        }

        if ($user->update() === false) {
            throw new \RuntimeException('Update User Vip Failed');
        }
    }

    /**
     * 处理打赏订单退款
     *
     * @param OrderModel $order
     */
    protected function handleRewardOrderRefund(OrderModel $order)
    {

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
     * 查找退款任务
     *
     * @param integer $limit
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    protected function findTasks($limit = 5)
    {
        $itemType = TaskModel::TYPE_REFUND;
        $status = TaskModel::STATUS_PENDING;
        $tryCount = self::TRY_COUNT;

        $tasks = TaskModel::query()
            ->where('item_type = :item_type:', ['item_type' => $itemType])
            ->andWhere('status = :status:', ['status' => $status])
            ->andWhere('try_count < :try_count:', ['try_count' => $tryCount])
            ->orderBy('priority ASC,try_count DESC')
            ->limit($limit)
            ->execute();

        return $tasks;
    }

}
