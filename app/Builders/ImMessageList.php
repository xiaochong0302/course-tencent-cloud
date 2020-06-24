<?php

namespace App\Builders;

use App\Repos\User as UserRepo;

class ImMessageList extends Builder
{

    public function handleSenders(array $messages)
    {
        $users = $this->getSenders($messages);

        foreach ($messages as $key => $message) {
            $messages[$key]['sender'] = $users[$message['sender_id']] ?? new \stdClass();
        }

        return $messages;
    }

    public function getSenders(array $messages)
    {
        $ids = kg_array_column($messages, 'sender_id');

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
