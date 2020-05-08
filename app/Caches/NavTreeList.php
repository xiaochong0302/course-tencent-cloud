<?php

namespace App\Caches;

use App\Builders\NavTreeList as NavTreeListBuilder;

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
            'top' => $builder->handle('top'),
            'bottom' => $builder->handle('bottom'),
        ];
    }

}
