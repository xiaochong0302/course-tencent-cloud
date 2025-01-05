<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use App\Caches\CategoryAllList as CategoryAllListCache;
use App\Models\Category as CategoryModel;

class ArticleList extends Builder
{

    public function handleArticles(array $articles)
    {
        foreach ($articles as $key => $article) {
            $articles[$key]['tags'] = json_decode($article['tags'], true);
        }

        return $articles;
    }

    public function handleCategories(array $articles)
    {
        $categories = $this->getCategories();

        foreach ($articles as $key => $article) {
            $articles[$key]['category'] = $categories[$article['category_id']] ?? null;
        }

        return $articles;
    }

    public function handleUsers(array $articles)
    {
        $users = $this->getUsers($articles);

        foreach ($articles as $key => $article) {
            $articles[$key]['owner'] = $users[$article['owner_id']] ?? null;
        }

        return $articles;
    }

    public function getCategories()
    {
        $cache = new CategoryAllListCache();

        $items = $cache->get(CategoryModel::TYPE_ARTICLE);

        if (empty($items)) return [];

        $result = [];

        foreach ($items as $item) {
            $result[$item['id']] = [
                'id' => $item['id'],
                'name' => $item['name'],
            ];
        }

        return $result;
    }

    public function getUsers($articles)
    {
        $ids = kg_array_column($articles, 'owner_id');

        return $this->getShallowUserByIds($ids);
    }

}
