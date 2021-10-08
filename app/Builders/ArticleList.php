<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use App\Caches\CategoryList as CategoryListCache;
use App\Models\Category as CategoryModel;
use App\Repos\User as UserRepo;

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
            $articles[$key]['category'] = $categories[$article['category_id']] ?? new \stdClass();
        }

        return $articles;
    }

    public function handleUsers(array $articles)
    {
        $users = $this->getUsers($articles);

        foreach ($articles as $key => $article) {
            $articles[$key]['owner'] = $users[$article['owner_id']] ?? new \stdClass();
        }

        return $articles;
    }

    public function getCategories()
    {
        $cache = new CategoryListCache();

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

        $userRepo = new UserRepo();

        $users = $userRepo->findShallowUserByIds($ids);

        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($users->toArray() as $user) {
            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
