<?php
/**
 * @copyright Copyright (c) 2023 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Console\Queues\Main as MainQueue;
use App\Models\Task as TaskModel;
use App\Providers\Database as DatabaseProvider;
use App\Providers\Redis as RedisProvider;
use App\Services\Queue as QueueService;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class QueueTask extends Task
{

    /**
     * @var array
     */
    protected array $lastAliveTime = [
        'main' => 0,
    ];

    /**
     * @var int
     */
    protected int $checkInterval = 60;

    /**
     * @var bool
     */
    protected bool $shouldStop = false;

    /**
     * 启动main消费队列
     *
     * @command php console.php queue main_worker
     */
    public function mainWorkerAction(): void
    {
        $this->registerSignals();

        $queueService = new QueueService();

        $queueName = $queueService->getMainQueueName();

        $logger = $this->getLogger('queue');

        $logger->info("------ {$queueName} worker start ------");

        $this->lastAliveTime['main'] = time();

        while (true) {

            $logger = $this->getLogger('queue');

            $this->dispatchSignals();

            if ($this->shouldStop) {
                $logger->info("------ {$queueName} worker stop gracefully ------");
                break;
            }

            try {

                if (time() - $this->lastAliveTime['main'] > $this->checkInterval) {
                    $this->keepRedisAlive();
                    $this->keepDbAlive();
                    $this->lastAliveTime['main'] = time();
                }

                $result = $queueService->pop($queueName, 5);

                if (!$result || !is_array($result)) {
                    continue;
                }

                $taskId = (int)$result[1];

                $logger->info("queue:{$queueName}, task:{$taskId} handling");

                $startTime = microtime(true);

                $manager = new MainQueue();

                $manager->handle($taskId);

                $costTime = microtime(true) - $startTime;

                if ($costTime > 5) {
                    $logger->warning("queue:{$queueName}, task:{$taskId} slow, cost: {$costTime} seconds");
                }

            } catch (\Exception $e) {

                $logger->error("queue:{$queueName} Exception: " . kg_json_encode([
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'message' => $e->getMessage(),
                    ]));
            }
        }
    }

    /**
     * 失败任务重新入列
     *
     * @command php console.php queue requeue
     */
    public function requeueAction(): void
    {
        $tasks = $this->findMainRequeueTasks();

        if ($tasks->count() > 0) {

            echo "queue:main, task count:{$tasks->count()}" . PHP_EOL;

            $queueService = new QueueService();

            $queueName = $queueService->getMainQueueName();

            foreach ($tasks as $task) {
                $task->try_count += 1;
                $task->update();
                $queueService->push($queueName, $task->id);
            }
        }
    }

    /**
     * 注册信号
     */
    protected function registerSignals(): void
    {
        if (function_exists('pcntl_signal')) {
            pcntl_signal(SIGTERM, [$this, 'handleStopSignal']);
            pcntl_signal(SIGINT, [$this, 'handleStopSignal']);
            pcntl_signal(SIGQUIT, [$this, 'handleStopSignal']);
        }
    }

    /**
     * 分发信号
     */
    protected function dispatchSignals(): void
    {
        if (function_exists('pcntl_signal')) {
            pcntl_signal_dispatch();
        }
    }

    /**
     * 停止信号
     */
    protected function handleStopSignal(): void
    {
        $this->shouldStop = true;
    }

    /**
     * 确保redis连接可用，不可用时重连
     */
    protected function keepRedisAlive(): void
    {
        $logger = $this->getLogger('queue');

        try {

            $redis = $this->getRedis();

            $redis->ping();

            $logger->info('keep redis connection alive');

        } catch (\RedisException $e) {

            $this->di->remove('redis');

            $redisProvider = new RedisProvider($this->di);

            $redisProvider->register();

            $logger->info('reconnect redis');
        }
    }

    /**
     * 确保db连接可用，不可用时重连
     */
    protected function keepDbAlive(): void
    {
        $logger = $this->getLogger('queue');

        try {

            $stmt = $this->db->query('SELECT 1');

            $stmt->fetchAll();

            unset($stmt);

            $logger->info('keep db connection alive');

        } catch (\PDOException $e) {

            $this->di->remove('db');

            $databaseProvider = new DatabaseProvider($this->di);

            $databaseProvider->register();

            $logger->error('db Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                ]));
        }
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|TaskModel[]
     */
    protected function findMainRequeueTasks(int $limit = 1000)
    {
        return TaskModel::query()
            ->where('status =:status:', ['status' => TaskModel::STATUS_FAILED])
            ->andWhere('try_count < 3')
            ->andWhere('item_type < 200')
            ->limit($limit)
            ->execute();
    }

}
