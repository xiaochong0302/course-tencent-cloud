<?php
/**
 * @copyright Copyright (c) 2023 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Queues;

use App\Console\Queues\Main\Deliver as DeliverService;
use App\Console\Queues\Main\RefundAfterFail as RefundAfterFailService;
use App\Console\Queues\Main\RefundAfterSuccess as RefundAfterSuccessService;
use App\Console\Queues\Main\RefundApply as RefundApplyService;
use App\Models\Task as TaskModel;

class Main extends Queue
{

    public function handle(int $id): void
    {
        $task = $this->findTaskWithRetry($id);

        if (!$task) return;

        try {

            switch ($task->item_type) {
                case TaskModel::TYPE_DELIVER:
                    $this->handleDeliver($task);
                    break;
                case TaskModel::TYPE_REFUND_APPLY:
                    $this->handleRefundApply($task);
                    break;
                case TaskModel::TYPE_REFUND_AFTER_SUCCESS:
                    $this->handleRefundAfterSuccess($task);
                    break;
                case TaskModel::TYPE_REFUND_AFTER_FAIL:
                    $this->handleRefundAfterFail($task);
                    break;
            }

            $task->status = TaskModel::STATUS_FINISHED;
            $task->update();

        } catch (\Exception $e) {

            $task->status = TaskModel::STATUS_FAILED;
            $task->update();

            $logger = $this->getLogger('queue');

            $logger->error('queue:main Process Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                    'taskId' => $id,
                ]));
        }
    }

    protected function handleDeliver(TaskModel $task): void
    {
        $service = new DeliverService();

        $service->handle($task);
    }

    protected function handleRefundApply(TaskModel $task): void
    {
        $service = new RefundApplyService();

        $service->handle($task);
    }

    protected function handleRefundAfterSuccess(TaskModel $task): void
    {
        $service = new RefundAfterSuccessService();

        $service->handle($task);
    }

    protected function handleRefundAfterFail(TaskModel $task): void
    {
        $service = new RefundAfterFailService();

        $service->handle($task);
    }

}
