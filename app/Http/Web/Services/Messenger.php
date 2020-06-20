<?php

namespace App\Http\Web\Services;

use App\Repos\User as UserRepo;
use App\Services\Frontend\UserTrait;
use GatewayClient\Gateway;

class Messenger extends Service
{

    use UserTrait;

    public function init()
    {
        $user = $this->getLoginUser();

        $mine = [
            'id' => $user->id,
            'username' => $user->name,
            'sign' => $user->sign,
            'avatar' => $user->avatar,
            'status' => 'online',
        ];

        $friend = $this->handleFriendList($user->id);

        $group = $this->handleGroupList($user->id);

        return [
            'mine' => $mine,
            'friend' => $friend,
            'group' => $group,
        ];
    }

    public function bindUser()
    {
        $user = $this->getLoginUser();

        $clientId = $this->request->getPost('client_id');

        Gateway::$registerAddress = '127.0.0.1:1238';

        Gateway::bindUid($clientId, $user->id);

        $userRepo = new UserRepo();

        $chatGroups = $userRepo->findImChatGroups($user->id);

        if ($chatGroups->count() > 0) {
            foreach ($chatGroups as $group) {
                Gateway::joinGroup($clientId, $this->getGroupName($group->id));
            }
        }

    }

    public function sendMessage()
    {
        $user = $this->getLoginUser();

        $from = $this->request->getPost('from');
        $to = $this->request->getPost('to');

        $content = [
            'username' => $from['username'],
            'avatar' => $from['avatar'],
            'content' => $from['content'],
            'fromid' => $from['id'],
            'id' => $from['id'],
            'type' => $to['type'],
            'timestamp' => 1000 * time(),
            'mine' => false,
        ];

        $message = json_encode([
            'type' => 'show_message',
            'content' => $content,
        ]);

        Gateway::$registerAddress = '127.0.0.1:1238';

        if ($to['type'] == 'friend') {

            Gateway::sendToUid($to['id'], $message);

        } elseif ($to['type'] == 'group') {

            $excludeClientId = null;

            if ($user->id == $from['id']) {
                $excludeClientId = Gateway::getClientIdByUid($user->id);
            }

            $groupName = $this->getGroupName($to['id']);

            Gateway::sendToGroup($groupName, $message, $excludeClientId);
        }
    }

    protected function handleFriendList($userId)
    {
        $userRepo = new UserRepo();

        $friendGroups = $userRepo->findImFriendGroups($userId);
        $friends = $userRepo->findImFriends($userId);

        $items = [];

        $items[] = ['id' => 0, 'groupname' => '我的好友', 'list' => []];

        if ($friendGroups->count() > 0) {
            foreach ($friendGroups as $group) {
                $items[] = ['id' => $group->id, 'groupname' => $group->name, 'online' => 0, 'list' => []];
            }
        }

        if ($friends->count() == 0) {
            return $items;
        }

        $userIds = kg_array_column($friends->toArray(), 'friend_id');

        $users = $userRepo->findByIds($userIds);

        $userMappings = [];

        foreach ($users as $user) {
            $userMappings[$user->id] = [
                'id' => $user->id,
                'username' => $user->name,
                'avatar' => $user->avatar,
                'sign' => $user->sign,
                'status' => 'online',
            ];
        }

        foreach ($items as $key => $item) {
            foreach ($friends as $friend) {
                $userId = $friend->friend_id;
                if ($item['id'] == $friend->group_id) {
                    $items[$key]['list'][] = $userMappings[$userId];
                } else {
                    $items[0]['list'][] = $userMappings[$userId];
                }
            }
        }

        return $items;
    }

    protected function handleGroupList($userId)
    {
        $userRepo = new UserRepo();

        $groups = $userRepo->findImChatGroups($userId);

        if ($groups->count() == 0) {
            return [];
        }

        $baseUrl = kg_ci_base_url();

        $result = [];

        foreach ($groups->toArray() as $group) {
            $group['avatar'] = $baseUrl . $group['avatar'];
            $result[] = [
                'id' => $group['id'],
                'groupname' => $group['name'],
                'avatar' => $group['avatar'],
            ];
        }

        return $result;
    }

    protected function getGroupName($groupId)
    {
        return "group_{$groupId}";
    }

}
