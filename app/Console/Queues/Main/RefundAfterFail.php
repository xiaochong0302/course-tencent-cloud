<?php
/**
 * @copyright Copyright (c) 2023 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Queues\Main;

use App\Models\Refund as RefundModel;
use App\Models\Task as TaskModel;
use App\Repos\Refund as RefundRepo;
use App\Traits\Service as ServiceTrait;
use Phalcon\Di\Injectable;

class RefundAfterFail extends Injectable
{

    use ServiceTrait;

    public function handle(TaskModel $task): void
    {
        echo '------ start refund after fail task ------' . PHP_EOL;

        $refundRepo = new RefundRepo();

        $refund = $refundRepo->findById($task->item_id);

        if (!$refund) {
            throw new \RuntimeException("Refund After Fail Task, Refund:{$task->item_id} Not Found");
        }

        $logger = $this->getLogger('refund');

        if ($refund->status == RefundModel::STATUS_FAILED) {
            $logger->warning("Refund:{$refund->id} already failed, skip duplicate processing");
            return;
        }

        $refund->status = RefundModel::STATUS_FAILED;

        $refund->update();

        echo '------ end refund after fail task ------' . PHP_EOL;
    }

}
