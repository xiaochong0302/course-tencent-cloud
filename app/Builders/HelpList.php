<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use App\Caches\CategoryAllList as CategoryAllListCache;
use App\Models\Category as CategoryModel;

class HelpList extends Builder
{

    public function handleCategories(array $helps)
    {
        $categories = $this->getCategories();

        foreach ($helps as $key => $help) {
            $helps[$key]['category'] = $categories[$help['category_id']] ?? null;
        }

        return $helps;
    }

    public function getCategories()
    {
        $cache = new CategoryAllListCache();

        $items = $cache->get(CategoryModel::TYPE_HELP);

        if (empty($items)) {
            return [];
        }

        $result = [];

        foreach ($items as $item) {
            $result[$item['id']] = [
                'id' => $item['id'],
                'name' => $item['name'],
            ];
        }

        return $result;
    }

}
