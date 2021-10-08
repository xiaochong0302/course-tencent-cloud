<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use App\Models\Nav as NavModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class NavTreeList extends Builder
{

    public function handle($position = 'top')
    {
        $topNavs = $this->findTopNavs($position);

        if ($topNavs->count() == 0) {
            return [];
        }

        $list = [];

        foreach ($topNavs as $nav) {
            $list[] = [
                'id' => $nav->id,
                'name' => $nav->name,
                'target' => $nav->target,
                'url' => $nav->url,
                'children' => $this->handleChildren($nav),
            ];
        }

        return $list;
    }

    protected function handleChildren(NavModel $nav)
    {
        $subNavs = $this->findSubNavs($nav->id);

        if ($subNavs->count() == 0) {
            return [];
        }

        $list = [];

        foreach ($subNavs as $nav) {
            $list[] = [
                'id' => $nav->id,
                'name' => $nav->name,
                'target' => $nav->target,
                'url' => $nav->url,
            ];
        }

        return $list;
    }

    /**
     * @param int $navId
     * @return ResultsetInterface|Resultset|NavModel[]
     */
    protected function findSubNavs($navId)
    {
        return NavModel::query()
            ->where('parent_id = :parent_id:', ['parent_id' => $navId])
            ->andWhere('published = 1')
            ->andWhere('deleted = 0')
            ->orderBy('priority ASC')
            ->execute();
    }

    /**
     * @param string $position
     * @return ResultsetInterface|Resultset|NavModel[]
     */
    protected function findTopNavs($position)
    {
        return NavModel::query()
            ->where('position = :position:', ['position' => $position])
            ->andWhere('level = 1')
            ->andWhere('published = 1')
            ->andWhere('deleted = 0')
            ->orderBy('priority ASC')
            ->execute();
    }

}
