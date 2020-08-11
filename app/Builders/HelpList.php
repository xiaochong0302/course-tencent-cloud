<?php

namespace App\Builders;

use App\Caches\CategoryList as CategoryListCache;
use App\Models\Category as CategoryModel;

class HelpList extends Builder
{

    public function handleCategories(array $helps)
    {
        $categories = $this->getCategories();

        foreach ($helps as $key => $help) {
            $helps[$key]['category'] = $categories[$help['category_id']] ?? new \stdClass();
        }

        return $helps;
    }

    public function getCategories()
    {
        $cache = new CategoryListCache();

        $items = $cache->get(CategoryModel::TYPE_HELP);

        if (!$items) return [];

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
