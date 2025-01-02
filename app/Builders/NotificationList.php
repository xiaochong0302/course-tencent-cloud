<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

class NotificationList extends Builder
{

    public function handleUsers(array $notifications)
    {
        $users = $this->getUsers($notifications);

        foreach ($notifications as $key => $notification) {
            $notifications[$key]['sender'] = $users[$notification['sender_id']] ?? null;
            $notifications[$key]['receiver'] = $users[$notification['receiver_id']] ?? null;
        }

        return $notifications;
    }

    public function getUsers(array $notifications)
    {
        $senderIds = kg_array_column($notifications, 'sender_id');
        $receiverIds = kg_array_column($notifications, 'receiver_id');
        $ids = array_merge($senderIds, $receiverIds);

        return $this->getShallowUserByIds($ids);
    }

}
