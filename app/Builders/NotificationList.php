<?php

namespace App\Builders;

use App\Repos\User as UserRepo;

class NotificationList extends Builder
{

    public function handleUsers(array $notifications)
    {
        $users = $this->getUsers($notifications);

        foreach ($notifications as $key => $notification) {
            $notifications[$key]['sender'] = $users[$notification['sender_id']] ?? new \stdClass();
            $notifications[$key]['receiver'] = $users[$notification['receiver_id']] ?? new \stdClass();
        }

        return $notifications;
    }

    public function getUsers(array $notifications)
    {
        $senderIds = kg_array_column($notifications, 'sender_id');
        $receiverIds = kg_array_column($notifications, 'receiver_id');
        $ids = array_merge($senderIds, $receiverIds);

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
