<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Search;

use App\Services\Logic\Service as LogicService;

abstract class Handler extends LogicService
{

    abstract function search();

    abstract function getHotQuery($limit, $type);

    abstract function getRelatedQuery($query, $limit);

    protected function handleKeywords($str)
    {
        return kg_substr($str, 0, 50, '');
    }

}
