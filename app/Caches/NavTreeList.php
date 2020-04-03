<?php

namespace App\Caches;

use App\Builders\NavTreeList as NavTreeListBuilder;
use App\Models\Nav as NavModel;
use Phalcon\Mvc\Model\Resultset;

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
        /**
         * @var Resultset $navs
         */
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
     * @param Resultset $navs
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

        $builder = new NavTreeListBuilder();

        return [
            'top' => $builder->handleTreeList($list['top']),
            'bottom' => $builder->handleTreeList($list['bottom']),
        ];
    }

}
