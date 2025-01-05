<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Help;

use App\Caches\CategoryList as CategoryListCache;
use App\Models\Category as CategoryModel;
use App\Repos\Help as HelpRepo;
use App\Services\Logic\Service as LogicService;

class HelpList extends LogicService
{

    public function handle()
    {
        $cache = new CategoryListCache();

        $categories = $cache->get(CategoryModel::TYPE_HELP);

        $helpRepo = new HelpRepo();

        $helps = $helpRepo->findAll([
            'published' => 1,
            'deleted' => 0,
        ]);

        $result = [];

        foreach ($categories as $category) {

            $item = [];

            $item['category'] = [
                'id' => $category['id'],
                'name' => $category['name'],
            ];

            $item['helps'] = [];

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

}
