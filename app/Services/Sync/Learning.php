<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Sync;

use App\Models\Learning as LearningModel;
use App\Services\Service as AppService;
use App\Traits\Client as ClientTrait;

class Learning extends AppService
{

    use ClientTrait;

    /**
     * @var int
     */
    protected $lifetime = 86400;

    public function addItem(LearningModel $learning, $intervalTime = 10)
    {
        $cache = $this->getCache();

        $redis = $this->getRedis();

        $itemKey = $this->getItemKey($learning->request_id);

        /**
         * @var LearningModel $cacheLearning
         */
        $cacheLearning = $cache->get($itemKey);

        if (!$cacheLearning) {

            $learning->client_type = $this->getClientType();
            $learning->client_ip = $this->getClientIp();
            $learning->duration = $intervalTime;
            $learning->active_time = time();

            $cache->save($itemKey, $learning, $this->lifetime);

        } else {

            $cacheLearning->duration += $intervalTime;
            $cacheLearning->position = $learning->position;
            $cacheLearning->active_time = time();

            $cache->save($itemKey, $cacheLearning, $this->lifetime);
        }

        $key = $this->getSyncKey();

        $redis->sAdd($key, $learning->request_id);
    }

    public function getItemKey($id)
    {
        return "learning:{$id}";
    }

    public function getSyncKey()
    {
        return 'sync_learning';
    }

}
