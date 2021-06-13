<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Models\CourseUser as CourseUserModel;
use App\Models\ImGroupUser as ImGroupUserModel;
use App\Models\PointGift as PointGiftModel;
use App\Models\PointRedeem as PointRedeemModel;
use App\Models\Task as TaskModel;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\ImGroup as ImGroupRepo;
use App\Repos\ImGroupUser as ImGroupUserRepo;
use App\Repos\PointGift as PointGiftRepo;
use App\Repos\PointRedeem as PointRedeemRepo;
use App\Services\Logic\Notice\DingTalk\PointRedeem as PointRedeemNotice;
use App\Services\Logic\Point\History\PointRefund as PointRefundPointHistory;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class PointGiftDeliverTask extends Task
{

    public function mainAction()
    {
        $logger = $this->getLogger('point');

        $tasks = $this->findTasks(30);

        if ($tasks->count() == 0) return;

        $redeemRepo = new PointRedeemRepo();

        foreach ($tasks as $task) {

            $redeemId = $task->item_info['point_redeem']['id'] ?? 0;

            $redeem = $redeemRepo->findById($redeemId);

            if (!$redeem) {
                $task->status = TaskModel::STATUS_FAILED;
                $task->update();
                break;
            }

            try {

                $this->db->begin();

                switch ($redeem->gift_type) {
                    case PointGiftModel::TYPE_COURSE:
                        $this->handleCourseRedeem($redeem);
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
    }

    protected function handleCourseRedeem(PointRedeemModel $redeem)
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

        $groupRepo = new ImGroupRepo();

        $group = $groupRepo->findByCourseId($course->id);

        if (!$group) {
            throw new \RuntimeException('Im Group Not Found');
        }

        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->findCourseUser($course->id, $redeem->user_id);

        if (!$courseUser) {

            $courseUser = new CourseUserModel();

            $courseUser->user_id = $redeem->user_id;
            $courseUser->course_id = $course->id;
            $courseUser->expiry_time = strtotime("+{$course->study_expiry} months");
            $courseUser->role_type = CourseUserModel::ROLE_STUDENT;
            $courseUser->source_type = CourseUserModel::SOURCE_POINT_REDEEM;

            if ($courseUser->create() === false) {
                throw new \RuntimeException('Create Course User Failed');
            }
        }

        $groupUserRepo = new ImGroupUserRepo();

        $groupUser = $groupUserRepo->findGroupUser($group->id, $redeem->user_id);

        if (!$groupUser) {

            $groupUser = new ImGroupUserModel();

            $groupUser->group_id = $group->id;
            $groupUser->user_id = $redeem->user_id;

            if ($groupUser->create() === false) {
                throw new \RuntimeException('Create Group User Failed');
            }
        }

        $redeem->status = PointRedeemModel::STATUS_FINISHED;

        if ($redeem->update() === false) {
            throw new \RuntimeException('Update Redeem Status Failed');
        }
    }

    protected function handleGoodsRedeem(PointRedeemModel $redeem)
    {
        $notice = new PointRedeemNotice();

        $notice->createTask($redeem);
    }

    protected function handlePointRefund(PointRedeemModel $redeem)
    {
        $service = new PointRefundPointHistory();

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
