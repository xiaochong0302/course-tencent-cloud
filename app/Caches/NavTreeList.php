<?php

namespace App\Caches;

use App\Builders\NavList as NavListBuilder;
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
        $navs = NavModel::query()
            ->where('published = 1 AND deleted = 0')
            ->orderBy('position ASC, priority ASC')
            ->execute();

        if ($navs->count() == 0) {
            return [];
        }

        return $this->handleContent($navs);
    }

    /**
     * @param \App\Models\Nav[] $navs
     * @return array
     */
    protected function handleContent($navs)
    {
        $list = [
            'top' => [],
            'bottom' => [],
        ];

        foreach ($navs->toArray() as $nav) {
            if ($nav['position'] == 'top') {
                $list['top'][] = $nav;
            } elseif ($nav['position'] == 'bottom') {
                $list['bottom'][] = $nav;
            }
        }

        $builder = new NavListBuilder();

        $content = [
            'top' => $builder->handleTreeList($list['top']),
            'bottom' => $builder->handleTreeList($list['bottom']),
        ];

        return $content;
    }

}
