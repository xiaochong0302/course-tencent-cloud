<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Services\Sync;

use App\Services\Service;

class ChapterIndex extends Service
{

    /**
     * @var int
     */
    protected int $lifetime = 86400;

    public function addItem(int $chapterId): void
    {
        $redis = $this->getRedis();

        $key = $this->getSyncKey();

        $redis->sAdd($key, $chapterId);

        if ($redis->sCard($key) == 1) {
            $redis->expire($key, $this->lifetime);
        }
    }

    public function getSyncKey(): string
    {
        return 'sync-chapter-index';
    }

}
