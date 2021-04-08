<?php

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

        $users = $userRepo->findByIds($ids, ['id', 'name', 'avatar']);

        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($users->toArray() as $user) {
            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
