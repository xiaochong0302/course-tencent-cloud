<?php

namespace App\Console\Queues;

use App\Models\Task as TaskModel;
use App\Repos\Task as TaskRepo;
use App\Traits\Service as ServiceTrait;
use Phalcon\Di\Injectable;
use Phalcon\Mvc\Model\Row;

class Queue extends Injectable
{

    use ServiceTrait;

    /**
     * 由于入队和事务时序问题，查询可能为空，使用延迟重试解决
     *
     * @param int $id
     * @param int $maxRetry
     * @return TaskModel|Row|null
     */
    protected function findTaskWithRetry(int $id, int $maxRetry = 10)
    {
        $taskRepo = new TaskRepo();

        for ($i = 0; $i < $maxRetry; $i++) {
            $delay = 100000 * rand(1, 5);
            $task = $taskRepo->findById($id);
            if ($task) return $task;
            usleep($delay);
        }

        return null;
    }

}
