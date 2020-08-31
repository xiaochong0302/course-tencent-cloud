<?php

namespace App\Console\Tasks;

use App\Models\CourseUser as CourseUserModel;
use App\Models\Order as OrderModel;
use App\Models\Refund as RefundModel;
use App\Models\Task as TaskModel;
use App\Models\Trade as TradeModel;
use App\Repos\Order as OrderRepo;
use App\Repos\User as UserRepo;
use App\Services\Smser\Order as OrderSmser;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class DeliverTask extends Task
{

    const TRY_COUNT = 3;

    public function mainAction()
    {
        $logger = $this->getLogger('order');

        $tasks = $this->findTasks();

        if ($tasks->count() == 0) {
            return;
        }

        $orderRepo = new OrderRepo();

        foreach ($tasks as $task) {

            /**
             * @var array $itemInfo
             */
            $itemInfo = $task->item_info;

            $order = $orderRepo->findById($itemInfo['order']['id']);

            if (!$order) continue;

            try {

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

                $this->finishOrder($order);

                $task->status = TaskModel::STATUS_FINISHED;

                $task->update();

            } catch (\Exception $e) {

                $task->try_count += 1;
                $task->priority += 1;

                if ($task->try_count > self::TRY_COUNT) {
                    $task->status = TaskModel::STATUS_FAILED;
                }

                $task->update();

                $logger->info('Order Process Exception ' . kg_json_encode([
                        'line' => $e->getLine(),
                        'code' => $e->getCode(),
                        'message' => $e->getMessage(),
                        'task' => $task->toArray(),
                    ]));
            }

            if ($task->status == TaskModel::STATUS_FINISHED) {
                $this->handleOrderNotice($order);
            } elseif ($task->status == TaskModel::STATUS_FAILED) {
                $this->handleOrderRefund($order);
            }
        }
    }

    protected function finishOrder(OrderModel $order)
    {
        $order->status = OrderModel::STATUS_FINISHED;

        if ($order->update() === false) {
            throw new \RuntimeException('Finish Order Failed');
        }
    }

    protected function handleCourseOrder(OrderModel $order)
    {
        /**
         * @var array $itemInfo
         */
        $itemInfo = $order->item_info;

        $data = [
            'user_id' => $order->owner_id,
            'course_id' => $order->item_id,
            'expiry_time' => $itemInfo['course']['study_expiry_time'],
            'role_type' => CourseUserModel::ROLE_STUDENT,
            'source_type' => CourseUserModel::SOURCE_CHARGE,
        ];

        $courseUser = new CourseUserModel();

        if ($courseUser->create($data) === false) {
            throw new \RuntimeException('Create Course User Failed');
        }
    }

    protected function handlePackageOrder(OrderModel $order)
    {
        /**
         * @var array $itemInfo
         */
        $itemInfo = $order->item_info;

        foreach ($itemInfo['courses'] as $course) {

            $data = [
                'user_id' => $order->owner_id,
                'course_id' => $course['id'],
                'expiry_time' => $course['study_expiry_time'],
                'role_type' => CourseUserModel::ROLE_STUDENT,
                'source_type' => CourseUserModel::SOURCE_CHARGE,
            ];

            $courseUser = new CourseUserModel();

            if ($courseUser->create($data) === false) {
                throw new \RuntimeException('Create Course User Failed');
            }
        }
    }

    protected function handleVipOrder(OrderModel $order)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($order->owner_id);

        /**
         * @var array $itemInfo
         */
        $itemInfo = $order->item_info;

        $user->vip_expiry_time = $itemInfo['vip']['expiry_time'];

        if ($user->update() === false) {
            throw new \RuntimeException('Update Vip Expiry Failed');
        }
    }

    protected function handleOrderNotice(OrderModel $order)
    {
        $smser = new OrderSmser();

        $smser->handle($order);
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
     * @param int $limit
     * @return ResultsetInterface|Resultset|TaskModel[]
     */
    protected function findTasks($limit = 100)
    {
        $itemType = TaskModel::TYPE_DELIVER;
        $status = TaskModel::STATUS_PENDING;
        $tryCount = self::TRY_COUNT;

        return TaskModel::query()
            ->where('item_type = :item_type:', ['item_type' => $itemType])
            ->andWhere('status = :status:', ['status' => $status])
            ->andWhere('try_count < :try_count:', ['try_count' => $tryCount + 1])
            ->orderBy('priority ASC')
            ->limit($limit)
            ->execute();
    }

}
