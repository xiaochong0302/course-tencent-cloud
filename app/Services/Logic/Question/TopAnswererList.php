<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Question;

use App\Caches\TopAnswererList as TopAnswererListCache;
use App\Services\Logic\Service as LogicService;

class TopAnswererList extends LogicService
{

    public function handle()
    {
        $cache = new TopAnswererListCache();

        $result = $cache->get();

        return $result ?: [];
    }

}
