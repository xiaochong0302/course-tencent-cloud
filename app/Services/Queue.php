<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services;

use Redis;

class Queue extends Service
{

    /**
     * @var Redis
     */
    protected Redis $redis;

    public function __construct()
    {
        $this->redis = $this->getRedis();
    }

    public function push(string $queue, string $data)
    {
        return $this->redis->lpush($queue, $data);
    }

    public function pop(string $queue, int $timeout = 5)
    {
        return $this->redis->brPop($queue, $timeout);
    }

    public function getMainQueueName(): string
    {
        return 'queue-main';
    }

    public function getNoticeQueueName(): string
    {
        return 'queue-notice';
    }

}
