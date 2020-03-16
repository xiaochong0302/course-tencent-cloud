<?php

namespace App\Console\Tasks;

use App\Models\CourseUser as CourseUserModel;
use App\Models\Order as OrderModel;
use App\Models\Task as TaskModel;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\User as UserRepo;
use Phalcon\Cli\Task;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class ProcessOrderTask extends Task
{

    const TRY_COUNT = 3;

    public function mainAction()
    {
        $tasks = $this->findTasks();

        if ($tasks->count() == 0) {
            return;
        }

        $orderRepo = new OrderRepo();

        foreach ($tasks as $task) {

            try {

                /**
                 * @var array $itemInfo
                 */
                $itemInfo = $task->item_info;

                $order = $orderRepo->findById($itemInfo['order']['id']);

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

            } catch (\Exception $e) {

                $task->try_count += 1;
                $task->priority += 1;

                if ($task->try_count > self::TRY_COUNT) {
                    $task->status = TaskModel::STATUS_FAILED;
                }

                $task->update();
            }
        }
    }

    /**
     * @param OrderModel $order
     */
    protected function handleCourseOrder(OrderModel $order)
    {
        /**
         * @var array $itemInfo
         */
        $itemInfo = $order->item_info;

        $data = [
            'user_id' => $order->user_id,
            'course_id' => $order->item_id,
            'expiry_time' => $itemInfo['course']['expiry_time'],
            'role_type' => CourseUserModel::ROLE_STUDENT,
            'source_type' => CourseUserModel::SOURCE_CHARGE,
        ];

        $courseUser = new CourseUserModel();

        if ($courseUser->create($data) === false) {
            throw new \RuntimeException('Create Course User Failed');
        }

        $this->handleCourseHistory($data['course_id'], $data['user_id']);
    }

    /**
     * @param OrderModel $order
     */
    protected function handlePackageOrder(OrderModel $order)
    {
        /**
         * @var array $itemInfo
         */
        $itemInfo = $order->item_info;

        foreach ($itemInfo['courses'] as $course) {

            $data = [
                'user_id' => $order->user_id,
                'course_id' => $course['id'],
                'expiry_time' => $course['expiry_time'],
                'role_type' => CourseUserModel::ROLE_STUDENT,
                'source_type' => CourseUserModel::SOURCE_CHARGE,
            ];

            $courseUser = new CourseUserModel();

            if ($courseUser->create($data) === false) {
                throw new \RuntimeException('Create Course User Failed');
            }

            $this->handleCourseHistory($data['course_id'], $data['user_id']);
        }
    }

    /**
     * @param OrderModel $order
     */
    protected function handleVipOrder(OrderModel $order)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($order->user_id);

        /**
         * @var array $itemInfo
         */
        $itemInfo = $order->item_info;

        $user->vip_expiry_time = $itemInfo['vip']['expiry_time'];

        if ($user->update() === false) {
            throw new \RuntimeException('Update Vip Expiry Failed');
        }
    }

    /**
     * @param int $courseId
     * @param int $userId
     */
    protected function handleCourseHistory($courseId, $userId)
    {
        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->findCourseStudent($courseId, $userId);

        if ($courseUser) {
            $courseUser->update(['deleted' => 1]);
        }

        $courseRepo = new CourseRepo();

        $userLearnings = $courseRepo->findUserLearnings($courseId, $userId);

        if ($userLearnings->count() > 0) {
            $userLearnings->update(['deleted' => 1]);
        }
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|TaskModel[]
     */
    protected function findTasks($limit = 100)
    {
        $itemType = TaskModel::TYPE_PROCESS_ORDER;
        $status = TaskModel::STATUS_PENDING;
        $tryCount = self::TRY_COUNT;

        $tasks = TaskModel::query()
            ->where('item_type = :item_type:', ['item_type' => $itemType])
            ->andWhere('status = :status:', ['status' => $status])
            ->andWhere('try_count < :try_count:', ['try_count' => $tryCount])
            ->orderBy('priority ASC')
            ->limit($limit)
            ->execute();

        return $tasks;
    }

}
