<?php

namespace App\Services\Logic\FlashSale;

use App\Services\Logic\FlashSaleTrait;
use App\Services\Logic\Service as LogicService;

class Queue extends LogicService
{

    use FlashSaleTrait;

    public function init($id)
    {
        $sale = $this->checkFlashSale($id);

        if ($sale->stock < 1) return;

        $redis = $this->getRedis();

        $keyName = $this->getKeyName($id);

        $ttl = $sale->end_time - time();

        $values = [];

        for ($i = 0; $i < $sale->stock; $i++) {
            $values[] = 1;
        }

        $redis->del($keyName);
        $redis->lPush($keyName, ...$values);
        $redis->expire($keyName, $ttl);
    }

    public function pop($id)
    {
        $redis = $this->getRedis();

        $keyName = $this->getKeyName($id);

        return $redis->lPop($keyName);
    }

    public function push($id)
    {
        $redis = $this->getRedis();

        $keyName = $this->getKeyName($id);

        return $redis->lPush($keyName, 1);
    }

    public function delete($id)
    {
        $redis = $this->getRedis();

        $keyName = $this->getKeyName($id);

        return $redis->del($keyName);
    }

    public function length($id)
    {
        $redis = $this->getRedis();

        $keyName = $this->getKeyName($id);

        return $redis->lLen($keyName);
    }

    protected function getKeyName($id)
    {
        return "flash_sale_queue:{$id}";
    }

}
