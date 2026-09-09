<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Library;

class Benchmark
{

    protected float $startTime = 0.0;

    protected float $endTime = 0.0;

    public function start(): void
    {
        $this->startTime = microtime(true);
    }

    public function stop(): void
    {
        $this->endTime = microtime(true);
    }

    public function getElapsedTime(): float
    {
        return $this->endTime - $this->startTime;
    }

}
