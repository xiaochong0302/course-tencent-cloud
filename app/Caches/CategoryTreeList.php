<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Builders\CategoryTreeList as CategoryTreeListBuilder;

class CategoryTreeList extends Cache
{

    /**
     * @var int
     */
    protected int $lifetime = 365 * 86400;

    public function getKey($id = null): string
    {
        return "category-tree-list-{$id}";
    }

    public function getContent($id = null): array
    {
        $builder = new CategoryTreeListBuilder();

        $list = $builder->handle($id);

        return $list ?: [];
    }

}
