<?php

namespace App\Http\Web\Services;

use App\Library\Cache\Backend\Redis as RedisCache;
use App\Services\Frontend\ChapterTrait;
use GatewayClient\Gateway;

class Live extends Service
{

    use ChapterTrait;

    public function getRecentChats($id)
    {
        $redis = $this->getRedis();

        $key = $this->getRedisListKey($id);

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

    public function getStats($id)
    {
        $chapter = $this->checkChapterCache($id);

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
        $clientId = $this->request->getPost('client_id');

        $chapter = $this->checkChapterCache($id);

        $user = $this->getCurrentUser();

        $groupName = $this->getGroupName($chapter->id);

        Gateway::$registerAddress = $this->getRegisterAddress();

        Gateway::joinGroup($clientId, $groupName);

        if ($user->id > 0) {

            Gateway::bindUid($clientId, $user->id);

            $message = kg_json_encode([
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
        $chapter = $this->checkChapterCache($id);

        $user = $this->getLoginUser();

        $content = $this->request->getPost('content', ['trim', 'striptags']);

        $content = kg_substr($content, 0, 80);

        Gateway::$registerAddress = $this->getRegisterAddress();

        $groupName = $this->getGroupName($chapter->id);

        $clientId = Gateway::getClientIdByUid($user->id);

        $message = [
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

        $key = $this->getRedisListKey($id);

        $redis->lPush($key, $encodeMessage);

        if ($redis->lLen($key) % 20 == 0) {
            $redis->lTrim($key, 0, 15);
        }

        return $message;
    }

    protected function getGroupName($id)
    {
        return "live_{$id}";
    }

    protected function getRegisterAddress()
    {
        $config = $this->getDI()->get('config');

        return $config->websocket->register_address;
    }

    protected function getRedisListKey($id)
    {
        return "live_recent_chat:{$id}";
    }

    protected function getRedis()
    {
        /**
         * @var RedisCache $cache
         */
        $cache = $this->getDI()->get('cache');

        return $cache->getRedis();
    }

}
