<?php

namespace App\Console\Tasks;

use App\Models\CourseUser as CourseUserModel;
use App\Models\ImGroupUser as ImGroupUserModel;
use App\Models\PointGift as PointGiftModel;
use App\Models\PointHistory as PointHistoryModel;
use App\Models\PointRedeem as PointRedeemModel;
use App\Models\Task as TaskModel;
use App\Repos\Course as CourseRepo;
use App\Repos\ImGroupUser as ImGroupUserRepo;
use App\Repos\PointGift as PointGiftRepo;
use App\Repos\PointRedeem as PointRedeemRepo;
use App\Repos\User as UserRepo;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class PointGiftAwardTask extends Task
{

    const TRY_COUNT = 3;

    public function mainAction()
    {
        $logger = $this->getLogger('point');

        $tasks = $this->findTasks();

        if ($tasks->count() == 0) {
            return;
        }

        $redeemRepo = new PointRedeemRepo();

        foreach ($tasks as $task) {

            $redeem = $redeemRepo->findById($task->item_id);

            if (!$redeem) continue;

            try {

                switch ($redeem->gift_type) {
                    case PointGiftModel::TYPE_COURSE:
                        $this->handleCourseAward($redeem);
                        break;
                    case PointGiftModel::TYPE_GOODS:
                        $this->handleCommodityAward($redeem);
                        break;
                }

                $this->finishRedeem($redeem);

                $task->status = TaskModel::STATUS_FINISHED;

                $task->update();

            } catch (\Exception $e) {

                $task->try_count += 1;
                $task->priority += 1;

                if ($task->try_count > self::TRY_COUNT) {
                    $task->status = TaskModel::STATUS_FAILED;
                }

                $task->update();

                $logger->info('Point Gift Award Exception ' . kg_json_encode([
                        'code' => $e->getCode(),
                        'message' => $e->getMessage(),
                    ]));
            }

            if ($task->status == TaskModel::STATUS_FINISHED) {
                $this->handleFinishNotice();
            } elseif ($task->status == TaskModel::STATUS_FAILED) {
                $this->handlePointRefund($redeem);
            }
        }
    }

    protected function finishRedeem(PointRedeemModel $redeem)
    {
        $redeem->status = PointRedeemModel::STATUS_FINISHED;

        if ($redeem->update() === false) {
            throw new \RuntimeException('Finish Point Redeem Failed');
        }
    }

    protected function handleCourseAward(PointRedeemModel $redeem)
    {
        $giftRepo = new PointGiftRepo();

        $gift = $giftRepo->findById($redeem->gift_id);

        $courseUser = new CourseUserModel();

        $courseUser->user_id = $redeem->user_id;
        $courseUser->course_id = $gift->attrs['id'];
        $courseUser->expiry_time = $gift->attrs['study_expiry_time'];
        $courseUser->role_type = CourseUserModel::ROLE_STUDENT;
        $courseUser->source_type = CourseUserModel::SOURCE_POINT_REDEEM;

        if ($courseUser->create() === false) {
            throw new \RuntimeException('Create Course User Failed');
        }

        $courseRepo = new CourseRepo();

        $group = $courseRepo->findImGroup($gift->attrs['id']);

        $groupUserRepo = new ImGroupUserRepo();

        $groupUser = $groupUserRepo->findGroupUser($group->id, $redeem->user_id);

        if ($groupUser) return;

        $groupUser = new ImGroupUserModel();

        $groupUser->group_id = $group->id;
        $groupUser->user_id = $redeem->user_id;

        if ($groupUser->create() === false) {
            throw new \RuntimeException('Create Im Group User Failed');
        }
    }

    protected function handleCommodityAward(PointRedeemModel $redeem)
    {

    }

    protected function handleFinishNotice()
    {

    }

    protected function handlePointRefund(PointRedeemModel $redeem)
    {
        $logger = $this->getLogger('point');

        $userRepo = new UserRepo();

        $balance = $userRepo->findUserBalance($redeem->user_id);

        try {

            $this->db->begin();

            $history = new PointHistoryModel();

            $eventInfo = [
                'gift' => [
                    'id' => $redeem->gift_id,
                    'name' => $redeem->gift_name,
                ]
            ];

            $history->user_id = $redeem->user_id;
            $history->event_id = $redeem->id;
            $history->event_type = PointHistoryModel::EVENT_POINT_REFUND;
            $history->event_info = $eventInfo;

            $result = $history->create();

            if ($result === false) {
                throw new \RuntimeException('Create Point History Failed');
            }

            $balance->point += $redeem->gift_point;

            $result = $balance->update();

            if ($result === false) {
                throw new \RuntimeException('Update User Balance Failed');
            }

            $this->db->commit();

        } catch (\Exception $e) {

            $this->db->rollback();

            $logger->error('Point Refund Exception ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));
        }
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|TaskModel[]
     */
    protected function findTasks($limit = 30)
    {
        $itemType = TaskModel::TYPE_POINT_GIFT_AWARD;
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
