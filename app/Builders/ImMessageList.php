<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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
