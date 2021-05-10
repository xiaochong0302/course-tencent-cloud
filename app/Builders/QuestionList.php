<?php

namespace App\Builders;

use App\Caches\CategoryList as CategoryListCache;
use App\Models\Category as CategoryModel;
use App\Repos\User as UserRepo;

class QuestionList extends Builder
{

    public function handleQuestions(array $questions)
    {
        foreach ($questions as $key => $question) {
            $questions[$key]['tags'] = json_decode($question['tags'], true);
        }

        return $questions;
    }

    public function handleCategories(array $articles)
    {
        $categories = $this->getCategories();

        foreach ($articles as $key => $article) {
            $articles[$key]['category'] = $categories[$article['category_id']] ?? new \stdClass();
        }

        return $articles;
    }

    public function handleUsers(array $questions)
    {
        $users = $this->getUsers($questions);

        foreach ($questions as $key => $question) {
            $questions[$key]['owner'] = $users[$question['owner_id']] ?? new \stdClass();
            $questions[$key]['last_replier'] = $users[$question['last_replier_id']] ?? new \stdClass();
        }

        return $questions;
    }

    public function getCategories()
    {
        $cache = new CategoryListCache();

        $items = $cache->get(CategoryModel::TYPE_QUESTION);

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

    public function getUsers($questions)
    {
        $ownerIds = kg_array_column($questions, 'owner_id');
        $lastReplierIds = kg_array_column($questions, 'last_replier_id');
        $ids = array_merge($ownerIds, $lastReplierIds);

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
