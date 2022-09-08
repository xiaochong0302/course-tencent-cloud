<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Models\PointGift as PointGiftModel;
use App\Models\PointGiftRedeem as PointGiftRedeemModel;
use App\Models\Task as TaskModel;
use App\Repos\Course as CourseRepo;
use App\Repos\PointGift as PointGiftRepo;
use App\Repos\PointGiftRedeem as PointGiftRedeemRepo;
use App\Repos\User as UserRepo;
use App\Repos\Vip as VipRepo;
use App\Services\Logic\Deliver\CourseDeliver as CourseDeliverService;
use App\Services\Logic\Deliver\VipDeliver as VipDeliverService;
use App\Services\Logic\Notice\External\DingTalk\PointGiftRedeem as PointGiftRedeemNotice;
use App\Services\Logic\Point\History\PointGiftRefund as PointGiftRefundPointHistory;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class PointGiftDeliverTask extends Task
{

    public function mainAction()
    {
        $logger = $this->getLogger('point');

        $tasks = $this->findTasks(30);

        echo sprintf('pending tasks: %s', $tasks->count()) . PHP_EOL;

        if ($tasks->count() == 0) return;

        echo '------ start deliver task ------' . PHP_EOL;

        $redeemRepo = new PointGiftRedeemRepo();

        foreach ($tasks as $task) {

            $redeem = $redeemRepo->findById($task->item_id);

            try {

                $this->db->begin();

                switch ($redeem->gift_type) {
                    case PointGiftModel::TYPE_COURSE:
                        $this->handleCourseRedeem($redeem);
                        break;
                    case PointGiftModel::TYPE_VIP:
                        $this->handleVipRedeem($redeem);
                        break;
                    case PointGiftModel::TYPE_GOODS:
                        $this->handleGoodsRedeem($redeem);
                        break;
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

                $logger->error('Point Gift Deliver Exception ' . kg_json_encode([
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'message' => $e->getMessage(),
                        'task' => $task->toArray(),
                    ]));
            }

            if ($task->status == TaskModel::STATUS_FAILED) {
                $this->handlePointRefund($redeem);
            }
        }

        echo '------ end deliver task ------' . PHP_EOL;
    }

    protected function handleCourseRedeem(PointGiftRedeemModel $redeem)
    {
        $giftRepo = new PointGiftRepo();

        $gift = $giftRepo->findById($redeem->gift_id);

        if (!$gift) {
            throw new \RuntimeException('Gift Not Found');
        }

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($gift->attrs['id']);

        if (!$course) {
            throw new \RuntimeException('Course Not Found');
        }

        $redeem->status = PointGiftRedeemModel::STATUS_FINISHED;

        if ($redeem->update() === false) {
            throw new \RuntimeException('Update Point Redeem Status Failed');
        }

        $userRepo = new UserRepo();

        $user = $userRepo->findById($redeem->user_id);

        $deliverService = new CourseDeliverService();

        $deliverService->handle($course, $user);
    }

    protected function handleVipRedeem(PointGiftRedeemModel $redeem)
    {
        $giftRepo = new PointGiftRepo();

        $gift = $giftRepo->findById($redeem->gift_id);

        if (!$gift) {
            throw new \RuntimeException('Gift Not Found');
        }

        $vipRepo = new VipRepo();

        $vip = $vipRepo->findById($gift->attrs['id']);

        if (!$vip) {
            throw new \RuntimeException('Vip Not Found');
        }

        $redeem->status = PointGiftRedeemModel::STATUS_FINISHED;

        if ($redeem->update() === false) {
            throw new \RuntimeException('Update Point Redeem Status Failed');
        }

        $userRepo = new UserRepo();

        $user = $userRepo->findById($redeem->user_id);

        $deliverService = new VipDeliverService();

        $deliverService->handle($vip, $user);
    }

    protected function handleGoodsRedeem(PointGiftRedeemModel $redeem)
    {
        $notice = new PointGiftRedeemNotice();

        $notice->createTask($redeem);
    }

    protected function handlePointRefund(PointGiftRedeemModel $redeem)
    {
        $service = new PointGiftRefundPointHistory();

        $service->handle($redeem);
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|TaskModel[]
     */
    protected function findTasks($limit = 30)
    {
        $itemType = TaskModel::TYPE_POINT_GIFT_DELIVER;
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
