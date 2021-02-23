<?php

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