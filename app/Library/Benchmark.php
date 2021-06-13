<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Library;

class Benchmark
{

    protected $startTime = 0;

    protected $endTime = 0;

    public function start()
    {
        $this->startTime = microtime(true);
    }

    public function stop()
    {
        $this->endTime = microtime(true);
    }

    public function getElapsedTime()
    {
        return $this->endTime - $this->startTime;
    }

}