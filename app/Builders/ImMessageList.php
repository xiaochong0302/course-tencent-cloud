<?php

namespace App\Builders;

use App\Repos\User as UserRepo;

class ImMessageList extends Builder
{

    public function handleUsers(array $messages)
    {
        $users = $this->getUsers($messages);

        foreach ($messages as $key => $message) {
            $messages[$key]['user'] = $users[$message['user_id']] ?? new \stdClass();
        }

        return $messages;
    }

    public function getUsers(array $messages)
    {
        $ids = kg_array_column($messages, 'user_id');

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($ids, ['id', 'name', 'avatar']);

        $baseUrl = kg_ci_base_url();

        $result = [];

        foreach ($users->toArray() as $user) {
            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
