<?php

namespace App\Builders;

use App\Repos\Tag as TagRepo;
use App\Repos\User as UserRepo;

class TagFollowList extends Builder
{

    public function handleTags(array $relations)
    {
        $tags = $this->getTags($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['tag'] = $tags[$value['tag_id']] ?? new \stdClass();
        }

        return $relations;
    }

    public function handleUsers(array $relations)
    {
        $users = $this->getUsers($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['user'] = $users[$value['user_id']] ?? new \stdClass();
        }

        return $relations;
    }

    public function getTags(array $relations)
    {
        $ids = kg_array_column($relations, 'tag_id');

        $tagRepo = new TagRepo();

        $columns = ['id', 'name', 'alias', 'icon', 'follow_count'];

        $tags = $tagRepo->findByIds($ids, $columns);

        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($tags->toArray() as $tag) {
            $tag['icon'] = $baseUrl . $tag['icon'];
            $result[$tag['id']] = $tag;
        }

        return $result;
    }

    public function getUsers(array $relations)
    {
        $ids = kg_array_column($relations, 'user_id');

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
