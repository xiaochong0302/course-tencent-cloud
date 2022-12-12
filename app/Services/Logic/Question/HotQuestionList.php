<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Question;

use App\Caches\HotQuestionList as HotQuestionListCache;
use App\Services\Logic\Service as LogicService;

class HotQuestionList extends LogicService
{

    public function handle()
    {
        $limit = $this->request->getQuery('limit', 'int', 10);

        $cache = new HotQuestionListCache();

        $list = $cache->get();

        if($limit < count($list)) {
            $list = array_slice($list, $limit);
        }

        return $list;
    }

}
