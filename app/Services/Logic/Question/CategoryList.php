<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Question;

use App\Caches\CategoryTreeList as CategoryTreeListCache;
use App\Models\Category as CategoryModel;
use App\Services\Logic\Service as LogicService;

class CategoryList extends LogicService
{

    public function handle()
    {
        $cache = new CategoryTreeListCache();

        return $cache->get(CategoryModel::TYPE_QUESTION);
    }

}
