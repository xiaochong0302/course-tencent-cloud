<?php

namespace App\Http\Web\Services;

use App\Services\Frontend\ChapterTrait;
use GatewayClient\Gateway;

class Messenger extends Service
{

    use ChapterTrait;

    public function bindUser($id)
    {
        $user = $this->getCurrentUser();

        $userId = $user->id > 0 ?: $this->session->getId();

        $clientId = $this->request->getPost('client_id');

        $groupName = $this->getGroupName($id);

        Gateway::$registerAddress = '127.0.0.1:1238';

        Gateway::bindUid($clientId, $userId);

        Gateway::joinGroup($clientId, $groupName);
    }

    public function sendMessage($id)
    {
        $chapter = $this->checkChapterCache($id);

        $from = $this->request->getPost('from');
        $to = $this->request->getPost('to');

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

        $groupName = $this->getGroupName($chapter->id);

        Gateway::$registerAddress = '127.0.0.1:1238';

        Gateway::sendToGroup($groupName, $message);
    }

    protected function getGroupName($groupId)
    {
        return "group_{$groupId}";
    }

}
