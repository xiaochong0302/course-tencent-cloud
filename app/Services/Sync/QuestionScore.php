<?php

namespace App\Services\Sync;

use App\Services\Service;

class QuestionScore extends Service
{

    /**
     * @var int
     */
    protected $lifetime = 86400;

    public function addItem($questionId)
    {
        $redis = $this->getRedis();

        $key = $this->getSyncKey();

        $redis->sAdd($key, $questionId);

        if ($redis->sCard($key) == 1) {
            $redis->expire($key, $this->lifetime);
        }
    }

    public function getSyncKey()
    {
        return 'sync_question_score';
    }

}
