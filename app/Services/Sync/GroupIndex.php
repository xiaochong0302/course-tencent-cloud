<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Sync;

use App\Services\Service;

class GroupIndex extends Service
{

    /**
     * @var int
     */
    protected $lifetime = 86400;

    public function addItem($groupId)
    {
        $redis = $this->getRedis();

        $key = $this->getSyncKey();

        $redis->sAdd($key, $groupId);

        if ($redis->sCard($key) == 1) {
            $redis->expire($key, $this->lifetime);
        }
    }

    public function getSyncKey()
    {
        return 'sync_group_index';
    }

}
