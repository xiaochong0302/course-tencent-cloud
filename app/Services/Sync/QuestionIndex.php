<?php

namespace App\Services\Sync;

use App\Services\Service;

class QuestionIndex extends Service
{

    /**
     * @var int
     */
    protected $lifetime = 86400;

    public function addItem($articleId)
    {
        $redis = $this->getRedis();

        $key = $this->getSyncKey();

        $redis->sAdd($key, $articleId);

        if ($redis->sCard($key) == 1) {
            $redis->expire($key, $this->lifetime);
        }
    }

    public function getSyncKey()
    {
        return 'sync_question_index';
    }

}
