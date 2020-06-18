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

        Gateway::$registerAddress = '127.0.0.1:1238';

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

        Gateway::$registerAddress = '127.0.0.1:1238';

        if ($user->id > 0) {
            Gateway::bindUid($clientId, $user->id);
        }

        Gateway::joinGroup($clientId, $groupName);
    }

    public function sendMessage($id)
    {
        $chapter = $this->checkChapterCache($id);

        $user = $this->getLoginUser();

        $from = $this->request->getPost('from');
        $to = $this->request->getPost('to');

        Gateway::$registerAddress = '127.0.0.1:1238';

        $groupName = $this->getGroupName($chapter->id);

        $excludeClientId = null;

        if ($user->id == $from['id']) {
            $excludeClientId = Gateway::getClientIdByUid($user->id);
        }

        $content = [
            'username' => $from['username'],
            'avatar' => $from['avatar'],
            'content' => $from['content'],
            'fromid' => $from['id'],
            'id' => $to['id'],
            'type' => $to['type'],
            'timestamp' => 1000 * time(),
            'mine' => false,
        ];

        $message = json_encode([
            'type' => 'show_message',
            'content' => $content,
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
                'title' => $user['title'],
                'vip' => $user['vip'],
                'avatar' => $user['avatar'],
            ];
        }

        return $result;
    }

    protected function getGroupName($groupId)
    {
        return "chapter_{$groupId}";
    }

}
