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

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "category_tree_list:{$id}";
    }

    public function getContent($id = null)
    {
        $builder = new CategoryTreeListBuilder();

        $list = $builder->handle($id);

        return $list ?: [];
    }

}
