<?php

namespace App\Http\Web\Services;

use App\Repos\User as UserRepo;
use App\Services\Frontend\ChapterTrait;
use GatewayClient\Gateway;

class Live extends Service
{

    use ChapterTrait;

    public function getStats($id)
    {
        $chapter = $this->checkChapterCache($id);

        Gateway::$registerAddress = $this->getRegisterAddress();

        $groupName = $this->getGroupName($chapter->id);

        $clientCount = Gateway::getClientIdCountByGroup($groupName);
        $userCount = Gateway::getUidCountByGroup($groupName);
        $guestCount = $clientCount - $userCount;

        $userIds = Gateway::getUidListByGroup($groupName);

        $users = $this->handleUsers($userIds);

        return [
            'user_count' => $userCount,
            'guest_count' => $guestCount,
            'users' => $users,
        ];
    }

    public function bindUser($id)
    {
        $clientId = $this->request->getPost('client_id');

        $chapter = $this->checkChapterCache($id);

        $user = $this->getCurrentUser();

        $groupName = $this->getGroupName($chapter->id);

        Gateway::$registerAddress = $this->getRegisterAddress();

        if ($user->id > 0) {
            Gateway::bindUid($clientId, $user->id);
        }

        Gateway::joinGroup($clientId, $groupName);
    }

    public function sendMessage($id)
    {
        $chapter = $this->checkChapterCache($id);

        $user = $this->getLoginUser();

        Gateway::$registerAddress = $this->getRegisterAddress();

        $groupName = $this->getGroupName($chapter->id);

        $excludeClientId = Gateway::getClientIdByUid($user->id);

        $message = json_encode([
            'type' => 'show_message',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
            ],
        ]);

        Gateway::sendToGroup($groupName, $message, $excludeClientId);
    }

    protected function handleUsers($userIds)
    {
        if (!$userIds) return [];

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($userIds);

        $baseUrl = kg_ci_base_url();

        $result = [];

        foreach ($users->toArray() as $key => $user) {
            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'vip' => $user['vip'],
                'avatar' => $user['avatar'],
            ];
        }

        return $result;
    }

    protected function getGroupName($groupId)
    {
        return "live_{$groupId}";
    }

    protected function getRegisterAddress()
    {
        $config = $this->getDI()->get('config');

        return $config->websocket->register_address;
    }

}
