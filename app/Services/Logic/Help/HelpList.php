<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Help;

use App\Caches\HelpList as HelpListCache;
use App\Services\Logic\Service as LogicService;

class HelpList extends LogicService
{

    public function handle()
    {
        $cache = new HelpListCache();

        return $cache->get();
    }

}
