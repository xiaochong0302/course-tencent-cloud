<?php

namespace App\Services;

use App\Models\Learning as LearningModel;
use App\Traits\Client as ClientTrait;

class LearningSyncer extends Service
{

    use ClientTrait;

    /**
     * @var \Phalcon\Cache\Backend
     */
    protected $cache;

    protected $lifetime = 86400;

    public function __construct()
    {
        $this->cache = $this->getDI()->get('cache');
    }

    public function save(LearningModel $learning, $timeout = 10)
    {
        // 兼容秒和毫秒
        if ($timeout > 1000) {
            $timeout = intval($timeout / 1000);
        }

        $key = $this->getKey($learning->request_id);

        $item = $this->cache->get($key);

        $clientIp = $this->getClientIp();
        $clientType = $this->getClientType();

        $content = [
            'request_id' => $learning->request_id,
            'course_id' => $learning->course_id,
            'chapter_id' => $learning->chapter_id,
            'user_id' => $learning->user_id,
            'position' => $learning->position,
            'client_type' => $clientType,
            'client_ip' => $clientIp,
        ];

        if (!$item) {
            $content['duration'] = $timeout;
            $this->cache->save($key, $content, $this->lifetime);
        } else {
            $content['duration'] = $item['duration'] + $timeout;
            $this->cache->save($key, $content, $this->lifetime);
        }
    }

    public function getKey($requestId)
    {
        return "learning:{$requestId}";
    }

}
