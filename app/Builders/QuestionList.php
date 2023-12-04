<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use App\Caches\CategoryAllList as CategoryAllListCache;
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

    public function handleCategories(array $questions)
    {
        $categories = $this->getCategories();

        foreach ($questions as $key => $question) {
            $questions[$key]['category'] = $categories[$question['category_id']] ?? new \stdClass();
        }

        return $questions;
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
        $cache = new CategoryAllListCache();

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
