<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Models\Category as CategoryModel;
use App\Models\Help as HelpModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class HelpList extends Cache
{

    protected $lifetime = 7 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'help_list';
    }

    public function getContent($id = null)
    {

        $categories = $this->findCategories();

        if ($categories->count() == 0) {
            return [];
        }

        $result = [];

        foreach ($categories as $category) {

            $item = [];

            $item['category'] = [
                'id' => $category->id,
                'name' => $category->name,
            ];

            $item['helps'] = [];

            $helps = $this->findHelps($category->id);

            if ($helps->count() > 0) {
                foreach ($helps as $help) {
                    $item['helps'][] = [
                        'id' => $help->id,
                        'title' => $help->title,
                    ];
                }
            }

            $result[] = $item;
        }

        return $result;
    }

    /**
     * @return ResultsetInterface|Resultset|CategoryModel[]
     */
    protected function findCategories()
    {
        return CategoryModel::query()
            ->where('type = :type:', ['type' => CategoryModel::TYPE_HELP])
            ->andWhere('level = 1')
            ->andWhere('published = 1')
            ->andWhere('deleted = 0')
            ->orderBy('priority ASC')
            ->execute();
    }

    /**
     * @param int $categoryId
     * @return ResultsetInterface|Resultset|CategoryModel[]
     */
    protected function findHelps($categoryId)
    {
        return HelpModel::query()
            ->where('category_id = :category_id:', ['category_id' => $categoryId])
            ->andWhere('published = 1')
            ->andWhere('deleted = 0')
            ->orderBy('priority ASC')
            ->execute();
    }

}
