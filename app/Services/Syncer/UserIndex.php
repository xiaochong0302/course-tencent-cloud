<?php

namespace App\Services\Syncer;

use App\Services\Service;

class UserIndex extends Service
{

    /**
     * @var int
     */
    protected $lifetime = 86400;

    public function addItem($userId)
    {
        $redis = $this->getRedis();

        $key = $this->getSyncKey();

        $redis->sAdd($key, $userId);

        if ($redis->sCard($key) == 1) {
            $redis->expire($key, $this->lifetime);
        }
    }

    public function getSyncKey()
    {
        return 'sync_user_index';
    }

}
