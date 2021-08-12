<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Models\Course as CourseModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\ImGroupUser as ImGroupUserModel;
use App\Models\Order as OrderModel;
use App\Models\Refund as RefundModel;
use App\Models\Task as TaskModel;
use App\Models\Trade as TradeModel;
use App\Repos\Course as CourseRepo;
use App\Repos\ImGroup as ImGroupRepo;
use App\Repos\ImGroupUser as ImGroupUserRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\Notice\OrderFinish as OrderFinishNotice;
use App\Services\Logic\Point\History\OrderConsume as OrderConsumePointHistory;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class DeliverTask extends Task
{

    public function mainAction()
    {
        $logger = $this->getLogger('order');

        $tasks = $this->findTasks(30);

        echo sprintf('pending tasks: %s', $tasks->count()) . PHP_EOL;

        if ($tasks->count() == 0) return;

        echo '------ start deliver task ------' . PHP_EOL;

        $orderRepo = new OrderRepo();

        foreach ($tasks as $task) {

            $orderId = $task->item_info['order']['id'] ?? 0;

            $order = $orderRepo->findById($orderId);

            if (!$order) {
                $task->status = TaskModel::STATUS_FAILED;
                $task->update();
                continue;
            }

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
                $task->priority += 1;

                if ($task->try_count > $task->max_try_count) {
                    $task->status = TaskModel::STATUS_FAILED;
                }

                $task->update();

                $logger->error('Order Process Exception ' . kg_json_encode([
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
        $course = $order->item_info['course'];

        if ($course['model'] == CourseModel::MODEL_OFFLINE) {
            $expiryTime = strtotime($course['attrs']['end_date']);
        } else {
            $expiryTime = $course['study_expiry_time'];
        }

        $courseUser = new CourseUserModel();

        $courseUser->user_id = $order->owner_id;
        $courseUser->course_id = $order->item_id;
        $courseUser->expiry_time = $expiryTime;
        $courseUser->role_type = CourseUserModel::ROLE_STUDENT;
        $courseUser->source_type = CourseUserModel::SOURCE_CHARGE;

        if ($courseUser->create() === false) {
            throw new \RuntimeException('Create Course User Failed');
        }

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($course['id']);

        $course->user_count += 1;

        $course->update();

        $groupRepo = new ImGroupRepo();

        $group = $groupRepo->findByCourseId($course->id);

        $groupUserRepo = new ImGroupUserRepo();

        $groupUser = $groupUserRepo->findGroupUser($group->id, $order->owner_id);

        if (!$groupUser) {

            $groupUser = new ImGroupUserModel();

            $groupUser->group_id = $group->id;
            $groupUser->user_id = $order->owner_id;

            if ($groupUser->create() === false) {
                throw new \RuntimeException('Create Group User Failed');
            }
        }
    }

    protected function handlePackageOrder(OrderModel $order)
    {
        $itemInfo = $order->item_info;

        foreach ($itemInfo['courses'] as $course) {

            $courseUser = new CourseUserModel();

            $courseUser->user_id = $order->owner_id;
            $courseUser->course_id = $course['id'];
            $courseUser->expiry_time = $course['study_expiry_time'];
            $courseUser->role_type = CourseUserModel::ROLE_STUDENT;
            $courseUser->source_type = CourseUserModel::SOURCE_CHARGE;

            if ($courseUser->create() === false) {
                throw new \RuntimeException('Create Course User Failed');
            }

            $courseRepo = new CourseRepo();

            $course = $courseRepo->findById($course['id']);

            $course->user_count += 1;

            $course->update();

            $groupRepo = new ImGroupRepo();

            $group = $groupRepo->findByCourseId($course->id);

            $groupUserRepo = new ImGroupUserRepo();

            $groupUser = $groupUserRepo->findGroupUser($group->id, $order->owner_id);

            if (!$groupUser) {

                $groupUser = new ImGroupUserModel();

                $groupUser->group_id = $group->id;
                $groupUser->user_id = $order->owner_id;

                if ($groupUser->create() === false) {
                    throw new \RuntimeException('Create Group User Failed');
                }
            }
        }
    }

    protected function handleVipOrder(OrderModel $order)
    {
        $itemInfo = $order->item_info;

        $userRepo = new UserRepo();

        $user = $userRepo->findById($order->owner_id);

        $user->vip_expiry_time = $itemInfo['vip']['expiry_time'];
        $user->vip = 1;

        if ($user->update() === false) {
            throw new \RuntimeException('Update Vip Expiry Failed');
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
    protected function findTasks($limit = 30)
    {
        $itemType = TaskModel::TYPE_DELIVER;
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
