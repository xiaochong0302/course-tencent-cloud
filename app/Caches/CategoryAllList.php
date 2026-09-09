<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Models\Category as CategoryModel;

class CategoryAllList extends Cache
{

    /**
     * @var int
     */
    protected int $lifetime = 365 * 86400;

    public function getKey($id = null): string
    {
        return "category-all-list-{$id}";
    }

    public function getContent($id = null): array
    {
        $categories = CategoryModel::query()
            ->columns(['id', 'parent_id', 'name', 'priority', 'level', 'path'])
            ->where('type = :type:', ['type' => $id])
            ->orderBy('level ASC, priority ASC')
            ->execute();

        if ($categories->count() == 0) {
            return [];
        }

        return $categories->toArray();
    }

}
