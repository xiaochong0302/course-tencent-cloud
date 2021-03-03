<?php

namespace App\Console\Tasks;

use App\Models\Task as TaskModel;
use App\Services\Logic\Notice\AccountLogin as AccountLoginNotice;
use App\Services\Logic\Notice\ConsultReply as ConsultReplyNotice;
use App\Services\Logic\Notice\DingTalk\ConsultCreate as ConsultCreateNotice;
use App\Services\Logic\Notice\DingTalk\CustomService as CustomServiceNotice;
use App\Services\Logic\Notice\DingTalk\ServerMonitor as ServerMonitorNotice;
use App\Services\Logic\Notice\DingTalk\TeacherLive as TeacherLiveNotice;
use App\Services\Logic\Notice\LiveBegin as LiveBeginNotice;
use App\Services\Logic\Notice\OrderFinish as OrderFinishNotice;
use App\Services\Logic\Notice\PointGoodsDeliver as PointGoodsDeliverNotice;
use App\Services\Logic\Notice\RefundFinish as RefundFinishNotice;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class NoticeTask extends Task
{

    public function mainAction()
    {
        $logger = $this->getLogger('notice');

        $tasks = $this->findTasks(300);

        if ($tasks->count() == 0) return;

        foreach ($tasks as $task) {

            try {

                switch ($task->item_type) {
                    case TaskModel::TYPE_NOTICE_ACCOUNT_LOGIN:
                        $this->handleAccountLoginNotice($task);
                        break;
                    case TaskModel::TYPE_NOTICE_LIVE_BEGIN:
                        $this->handleLiveBeginNotice($task);
                        break;
                    case TaskModel::TYPE_NOTICE_ORDER_FINISH:
                        $this->handleOrderFinishNotice($task);
                        break;
                    case TaskModel::TYPE_NOTICE_REFUND_FINISH:
                        $this->handleRefundFinishNotice($task);
                        break;
                    case TaskModel::TYPE_NOTICE_CONSULT_REPLY:
                        $this->handleConsultReplyNotice($task);
                        break;
                    case TaskModel::TYPE_NOTICE_POINT_GOODS_DELIVER:
                        $this->handlePointGoodsDeliverNotice($task);
                        break;
                    case TaskModel::TYPE_NOTICE_CONSULT_CREATE:
                        $this->handleConsultCreateNotice($task);
                        break;
                    case TaskModel::TYPE_NOTICE_TEACHER_LIVE:
                        $this->handleTeacherLiveNotice($task);
                        break;
                    case TaskModel::TYPE_NOTICE_SERVER_MONITOR:
                        $this->handleServerMonitorNotice($task);
                        break;
                    case TaskModel::TYPE_NOTICE_CUSTOM_SERVICE:
                        $this->handleCustomServiceNotice($task);
                        break;
                }

                $task->status = TaskModel::STATUS_FINISHED;

                $task->update();

            } catch (\Exception $e) {

                $task->try_count += 1;
                $task->priority += 1;

                if ($task->try_count >= $task->max_try_count) {
                    $task->status = TaskModel::STATUS_FAILED;
                }

                $task->update();

                $logger->info('Notice Process Exception ' . kg_json_encode([
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'message' => $e->getMessage(),
                        'task' => $task->toArray(),
                    ]));
            }
        }
    }

    protected function handleAccountLoginNotice(TaskModel $task)
    {
        $notice = new AccountLoginNotice();

        $notice->handleTask($task);
    }

    protected function handleLiveBeginNotice(TaskModel $task)
    {
        $notice = new LiveBeginNotice();

        $notice->handleTask($task);
    }

    protected function handleOrderFinishNotice(TaskModel $task)
    {
        $notice = new OrderFinishNotice();

        $notice->handleTask($task);
    }

    protected function handleRefundFinishNotice(TaskModel $task)
    {
        $notice = new RefundFinishNotice();

        $notice->handleTask($task);
    }

    protected function handleConsultReplyNotice(TaskModel $task)
    {
        $notice = new ConsultReplyNotice();

        $notice->handleTask($task);
    }

    protected function handlePointGoodsDeliverNotice(TaskModel $task)
    {
        $notice = new PointGoodsDeliverNotice();

        $notice->handleTask($task);
    }

    protected function handleConsultCreateNotice(TaskModel $task)
    {
        $notice = new ConsultCreateNotice();

        $notice->handleTask($task);
    }

    protected function handleTeacherLiveNotice(TaskModel $task)
    {
        $notice = new TeacherLiveNotice();

        $notice->handleTask($task);
    }

    protected function handleServerMonitorNotice(TaskModel $task)
    {
        $notice = new ServerMonitorNotice();

        $notice->handleTask($task);
    }

    protected function handleCustomServiceNotice(TaskModel $task)
    {
        $notice = new CustomServiceNotice();

        $notice->handleTask($task);
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|TaskModel[]
     */
    protected function findTasks($limit = 300)
    {
        $itemTypes = [
            TaskModel::TYPE_NOTICE_ACCOUNT_LOGIN,
            TaskModel::TYPE_NOTICE_LIVE_BEGIN,
            TaskModel::TYPE_NOTICE_ORDER_FINISH,
            TaskModel::TYPE_NOTICE_REFUND_FINISH,
            TaskModel::TYPE_NOTICE_CONSULT_REPLY,
            TaskModel::TYPE_NOTICE_POINT_GOODS_DELIVER,
            TaskModel::TYPE_NOTICE_LUCKY_GOODS_DELIVER,
            TaskModel::TYPE_NOTICE_CONSULT_CREATE,
            TaskModel::TYPE_NOTICE_TEACHER_LIVE,
            TaskModel::TYPE_NOTICE_SERVER_MONITOR,
            TaskModel::TYPE_NOTICE_CUSTOM_SERVICE,
        ];

        $status = TaskModel::STATUS_PENDING;

        $createTime = strtotime('-1 days');

        return TaskModel::query()
            ->inWhere('item_type', $itemTypes)
            ->andWhere('status = :status:', ['status' => $status])
            ->andWhere('create_time > :create_time:', ['create_time' => $createTime])
            ->orderBy('priority ASC')
            ->limit($limit)
            ->execute();
    }

}
