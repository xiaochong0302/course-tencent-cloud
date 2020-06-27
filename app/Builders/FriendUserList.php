<?php

namespace App\Builders;

use App\Repos\User as UserRepo;

class FriendUserList extends Builder
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

        $users = $userRepo->findByIds($ids, ['id', 'name', 'avatar', 'about', 'vip']);

        $baseUrl = kg_ci_base_url();

        $result = [];

        foreach ($users->toArray() as $user) {
            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
