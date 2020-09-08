<?php

namespace App\Builders;

use App\Repos\User as UserRepo;

class ImFriendUserList extends Builder
{

    public function handleFriends(array $relations)
    {
        $users = $this->getFriends($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['friend'] = $users[$value['friend_id']] ?? new \stdClass();
        }

        return $relations;
    }

    public function getFriends(array $relations)
    {
        $ids = kg_array_column($relations, 'friend_id');

        $userRepo = new UserRepo();

        $columns = [
            'id', 'name', 'avatar', 'title', 'about', 'vip',
            'gender', 'area', 'active_time',
        ];

        $users = $userRepo->findByIds($ids, $columns);

        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($users->toArray() as $user) {
            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
