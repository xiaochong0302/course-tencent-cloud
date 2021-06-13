<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Builders\NavTreeList as NavTreeListBuilder;
use App\Models\Nav as NavModel;

class NavTreeList extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'nav_tree_list';
    }

    public function getContent($id = null)
    {
        $builder = new NavTreeListBuilder();

        return [
            'top' => $builder->handle(NavModel::POS_TOP),
            'bottom' => $builder->handle(NavModel::POS_BOTTOM),
        ];
    }

}
