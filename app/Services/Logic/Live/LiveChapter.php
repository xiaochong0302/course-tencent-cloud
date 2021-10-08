<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Live;

use App\Services\Logic\ChapterTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Live as LiveValidator;
use GatewayClient\Gateway;

class LiveChapter extends LogicService
{

    use ChapterTrait;

    public function getRecentChats($id)
    {
        $redis = $this->getRedis();

        $key = $this->getRecentChatKey($id);

        $redis->expire($key, 3 * 3600);

        $items = $redis->lRange($key, 0, 15);

        $result = [];

        if ($items) {
            foreach (array_reverse($items) as $item) {
                $result[] = json_decode($item, true);
            }
        }

        return $result;
    }

    public function getStatus($id)
    {
        $chapterLive = $this->checkChapterLive($id);

        return $chapterLive->status;
    }

    public function getStats($id)
    {
        $chapter = $this->checkChapter($id);

        Gateway::$registerAddress = $this->getRegisterAddress();

        $groupName = $this->getGroupName($chapter->id);

        $clientCount = Gateway::getClientIdCountByGroup($groupName);
        $userCount = Gateway::getUidCountByGroup($groupName);
        $guestCount = $clientCount - $userCount;

        return [
            'client_count' => $clientCount,
            'user_count' => $userCount,
            'guest_count' => $guestCount,
        ];
    }

    public function bindUser($id)
    {
        $clientId = $this->request->getPost('client_id', 'string');

        $chapter = $this->checkChapter($id);

        $user = $this->getCurrentUser();

        $groupName = $this->getGroupName($chapter->id);

        Gateway::$registerAddress = $this->getRegisterAddress();

        Gateway::joinGroup($clientId, $groupName);

        if ($user->id > 0) {

            Gateway::bindUid($clientId, $user->id);

            $message = kg_json_encode([
                'id' => kg_uniqid(),
                'type' => 'new_user',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'vip' => $user->vip,
                ],
            ]);

            Gateway::sendToGroup($groupName, $message, $clientId);
        }
    }

    public function sendMessage($id)
    {
        $chapter = $this->checkChapter($id);

        $user = $this->getLoginUser();

        $content = $this->request->getPost('content', ['trim', 'striptags']);

        $validator = new LiveValidator();

        $validator->checkMessage($content);

        Gateway::$registerAddress = $this->getRegisterAddress();

        $groupName = $this->getGroupName($chapter->id);

        $clientId = Gateway::getClientIdByUid($user->id);

        $message = [
            'id' => kg_uniqid(),
            'type' => 'new_message',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'vip' => $user->vip,
            ],
            'content' => $content,
        ];

        $encodeMessage = kg_json_encode($message);

        Gateway::sendToGroup($groupName, $encodeMessage, $clientId);

        $redis = $this->getRedis();

        $key = $this->getRecentChatKey($id);

        $redis->lPush($key, $encodeMessage);

        if ($redis->lLen($key) % 20 == 0) {
            $redis->lTrim($key, 0, 15);
        }

        return $message;
    }

    protected function getRegisterAddress()
    {
        $config = $this->getConfig();

        return $config->path('websocket.register_address');
    }

    protected function getRecentChatKey($id)
    {
        return "recent_live_chat:{$id}";
    }

    protected function getGroupName($id)
    {
        return "live_{$id}";
    }

}
