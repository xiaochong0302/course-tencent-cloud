<?php

namespace App\Http\Web\Services;

use App\Builders\ImMessageList as ImMessageListBuilder;
use App\Caches\ImNewGroupList as ImNewGroupListCache;
use App\Caches\ImNewUserList as ImNewUserListCache;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\ImFriendMessage as ImFriendMessageModel;
use App\Models\ImGroupMessage as ImGroupMessageModel;
use App\Models\User as UserModel;
use App\Repos\ImFriendMessage as ImFriendMessageRepo;
use App\Repos\ImGroup as ImGroupRepo;
use App\Repos\ImGroupMessage as ImGroupMessageRepo;
use App\Repos\ImSystemMessage as ImSystemMessageRepo;
use App\Repos\User as UserRepo;
use App\Validators\ImFriendUser as ImFriendUserValidator;
use App\Validators\ImGroup as ImGroupValidator;
use App\Validators\ImGroupUser as ImGroupUserValidator;
use App\Validators\ImMessage as ImMessageValidator;
use App\Validators\User as UserValidator;
use GatewayClient\Gateway;

/**
 * 警告：
 * user对象有更新操作会导致afterFetch()中的设置失效，
 * 有相关依赖的要重新调用一次afterFetch()
 */
class Im extends Service
{

    use ImFriendTrait, ImGroupTrait;

    public function init()
    {
        $user = $this->getLoginUser();

        $user->afterFetch();

        $mine = [
            'id' => $user->id,
            'username' => $user->name,
            'avatar' => $user->avatar,
            'sign' => $user->im['sign'] ?? '',
            'status' => $user->im['online']['status'] ?? 'online',
        ];

        $friend = $this->handleFriendList($user);
        $group = $this->handleGroupList($user);

        return [
            'mine' => $mine,
            'friend' => $friend,
            'group' => $group,
        ];
    }

    public function searchUsers($name)
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['name'] = $name;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $userRepo = new UserRepo();

        $pager = $userRepo->paginate($params, $sort, $page, $limit);

        return $this->handleUserPager($pager);
    }

    public function searchGroups($name)
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['name'] = $name;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $groupRepo = new ImGroupRepo();

        $pager = $groupRepo->paginate($params, $sort, $page, $limit);

        return $this->handleGroupPager($pager);
    }

    public function getNewUsers()
    {
        $cache = new ImNewUserListCache();

        $items = $cache->get();

        $pager = new \stdClass();

        $pager->total_items = count($items);
        $pager->total_pages = 1;
        $pager->items = $items;

        return $pager;
    }

    public function getNewGroups()
    {
        $cache = new ImNewGroupListCache();

        $items = $cache->get();

        $pager = new \stdClass();

        $pager->total_items = count($items);
        $pager->total_pages = 1;
        $pager->items = $items;

        return $pager;
    }

    public function getGroupUsers()
    {
        $id = $this->request->getQuery('id');

        $validator = new ImGroupValidator();

        $group = $validator->checkGroupCache($id);

        $groupRepo = new ImGroupRepo();

        $users = $groupRepo->findGroupUsers($group->id);

        if ($users->count() == 0) {
            return [];
        }

        $baseUrl = kg_ci_base_url();

        $result = [];

        foreach ($users->toArray() as $user) {
            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[] = [
                'id' => $user['id'],
                'username' => $user['name'],
                'avatar' => $user['avatar'],
                'sign' => $user['sign'],
            ];
        }

        return $result;
    }

    public function pullUnreadFriendMessages()
    {
        $user = $this->getLoginUser();

        $id = $this->request->getQuery('id');

        $validator = new UserValidator();

        $friend = $validator->checkUser($id);

        $userRepo = new UserRepo();

        $messages = $userRepo->findUnreadImFriendMessages($user->id, $friend->id);

        if ($messages->count() == 0) {
            return;
        }

        Gateway::$registerAddress = $this->getRegisterAddress();

        foreach ($messages as $message) {

            $message->update(['viewed' => 1]);

            $content = kg_json_encode([
                'type' => 'show_chat_msg',
                'message' => [
                    'username' => $friend->name,
                    'avatar' => $friend->avatar,
                    'content' => $message->content,
                    'fromid' => $friend->id,
                    'id' => $friend->id,
                    'timestamp' => 1000 * $message->create_time,
                    'type' => 'friend',
                    'mine' => false,
                ],
            ]);

            Gateway::sendToUid($user->id, $content);
        }
    }

    public function countUnreadSystemMessages()
    {
        $user = $this->getLoginUser();

        $userRepo = new UserRepo();

        return $userRepo->countUnreadImSystemMessages($user->id);
    }

    public function getSystemMessages()
    {
        $user = $this->getLoginUser();

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['receiver_id'] = $user->id;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $messageRepo = new ImSystemMessageRepo();

        return $messageRepo->paginate($params, $sort, $page, $limit);
    }

    public function getChatMessages()
    {
        $user = $this->getLoginUser();

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $validator = new ImMessageValidator();

        $validator->checkType($params['type']);

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        if ($params['type'] == 'friend') {

            $params['chat_id'] = ImFriendMessageModel::getChatId($user->id, $params['id']);

            $messageRepo = new ImFriendMessageRepo();

            $pager = $messageRepo->paginate($params, $sort, $page, $limit);

            return $this->handleChatMessagePager($pager);

        } elseif ($params['type'] == 'group') {

            $params['group_id'] = $params['id'];

            $messageRepo = new ImGroupMessageRepo();

            $pager = $messageRepo->paginate($params, $sort, $page, $limit);

            return $this->handleChatMessagePager($pager);
        }
    }

    public function getFriendStatus()
    {
        $user = $this->getLoginUser();

        $user->afterFetch();

        $id = $this->request->getQuery('id');

        $validator = new UserValidator();

        $friend = $validator->checkUser($id);

        $status = $friend->im['online']['status'] ?? 'unknown';

        /**
         * 对方设置隐身，不返回真实情况
         */
        if ($status == 'hide') {
            return 'unknown';
        }

        Gateway::$registerAddress = $this->getRegisterAddress();

        if (Gateway::isUidOnline($friend->id)) {
            $status = 'online';
        }

        return $status;
    }

    public function bindUser()
    {
        $user = $this->getLoginUser();

        $user->afterFetch();

        $clientId = $this->request->getPost('client_id');

        Gateway::$registerAddress = $this->getRegisterAddress();

        Gateway::bindUid($clientId, $user->id);

        $userRepo = new UserRepo();

        $chatGroups = $userRepo->findImGroups($user->id);

        if ($chatGroups->count() > 0) {
            foreach ($chatGroups as $group) {
                Gateway::joinGroup($clientId, $this->getGroupName($group->id));
            }
        }

        /**
         * 保持上次的在线状态
         */
        $status = $user->im['online']['status'] ?? 'online';

        $this->pushFriendOnlineTips($user, $status);
    }

    public function sendMessage()
    {
        $user = $this->getLoginUser();

        $from = $this->request->getPost('from');
        $to = $this->request->getPost('to');

        $validator = new ImMessageValidator();

        $from['content'] = $validator->checkContent($from['content']);

        $message = [
            'username' => $from['username'],
            'avatar' => $from['avatar'],
            'content' => $from['content'],
            'fromid' => $from['id'],
            'id' => $from['id'],
            'type' => $to['type'],
            'timestamp' => 1000 * time(),
            'mine' => false,
        ];

        if ($to['type'] == 'group') {
            $message['id'] = $to['id'];
        }

        $content = json_encode([
            'type' => 'show_chat_msg',
            'message' => $message,
        ]);

        Gateway::$registerAddress = $this->getRegisterAddress();

        if ($to['type'] == 'friend') {

            $validator = new ImFriendUserValidator();

            $relation = $validator->checkFriendUser($to['id'], $user->id);

            /**
             * 被对方屏蔽，忽略消息
             */
            if ($relation->blocked) {
                return;
            }

            $online = Gateway::isUidOnline($to['id']);

            $messageModel = new ImFriendMessageModel();

            $messageModel->create([
                'sender_id' => $from['id'],
                'receiver_id' => $to['id'],
                'content' => $from['content'],
                'viewed' => $online ? 1 : 0,
            ]);

            if ($online) {
                Gateway::sendToUid($to['id'], $content);
            } else {
                $relation->update(['msg_count' => $relation->msg_count + 1]);
            }

        } elseif ($to['type'] == 'group') {

            $validator = new ImGroupUserValidator();

            $relation = $validator->checkGroupUser($user->id, $to['id']);

            /**
             * 被对方屏蔽，忽略消息
             */
            if ($relation->blocked) {
                return;
            }

            $messageModel = new ImGroupMessageModel();

            $messageModel->create([
                'sender_id' => $from['id'],
                'group_id' => $to['id'],
                'content' => $from['content'],
            ]);

            $excludeClientId = null;

            /**
             * 不推送自己在群组中发的消息
             */
            if ($user->id == $from['id']) {
                $excludeClientId = Gateway::getClientIdByUid($user->id);
            }

            $groupName = $this->getGroupName($to['id']);

            Gateway::sendToGroup($groupName, $content, $excludeClientId);
        }
    }

    public function readSystemMessages()
    {
        $user = $this->getLoginUser();

        $userRepo = new UserRepo();

        $messages = $userRepo->findUnreadImSystemMessages($user->id);

        if ($messages->count() > 0) {
            foreach ($messages as $message) {
                $message->viewed = 1;
                $message->update();
            }
        }
    }

    public function updateOnline()
    {
        $user = $this->getLoginUser();

        $user->afterFetch();

        $status = $this->request->getPost('status');

        $im = $user->im ?: [];

        $im['online']['status'] = $status;

        $user->update(['im' => $im]);

        $this->pushFriendOnlineTips($user, $status);

        return $user;
    }

    public function updateSignature()
    {
        $user = $this->getLoginUser();

        $user->afterFetch();

        $sign = $this->request->getPost('sign');

        $validator = new UserValidator();

        $sign = $validator->checkImSign($sign);

        $im = $user->im ?? [];

        $im['sign'] = $sign;

        $user->update(['im' => $im]);

        return $user;
    }

    public function updateSkin()
    {
        $user = $this->getLoginUser();

        $user->afterFetch();

        $skin = $this->request->getPost('skin');

        $validator = new UserValidator();

        $skin = $validator->checkImSkin($skin);

        $im = $user->im ?? [];

        $im['skin'] = $skin;

        $user->update(['im' => $im]);

        return $user;
    }

    protected function pushFriendOnlineTips(UserModel $user, $status)
    {
        $user->afterFetch();

        $time = $user->im['online']['time'] ?? 0;
        $expired = time() - $time > 600;

        /**
         * 检查间隔，避免频繁提醒干扰
         */
        if ($time > 0 && !$expired) {
            return;
        }

        $im = $user->im ?: [];

        $im['online']['status'] = $status;
        $im['online']['time'] = time();

        $user->update(['im' => $im]);

        $userRepo = new UserRepo();

        $friendUsers = $userRepo->findImFriendUsers($user->id);

        if ($friendUsers->count() == 0) {
            return;
        }

        Gateway::$registerAddress = $this->getRegisterAddress();

        foreach ($friendUsers as $friendUser) {
            if (Gateway::isUidOnline($friendUser->friend_id)) {
                $content = kg_json_encode([
                    'type' => 'show_online_tips',
                    'friend' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'avatar' => $user->avatar,
                    ],
                    'status' => $status == 'online' ? 'online' : 'offline',
                ]);
                Gateway::sendToUid($friendUser->friend_id, $content);
            }
        }
    }

    protected function handleFriendList(UserModel $user)
    {
        $userRepo = new UserRepo();

        $friendGroups = $userRepo->findImFriendGroups($user->id);
        $friendUsers = $userRepo->findImFriendUsers($user->id);

        $items = [];

        $items[] = [
            'id' => 0,
            'groupname' => '我的好友',
            'list' => [],
        ];

        if ($friendGroups->count() > 0) {
            foreach ($friendGroups as $group) {
                $items[] = [
                    'id' => $group->id,
                    'groupname' => $group->name,
                    'online' => 0,
                    'list' => [],
                ];
            }
        }

        if ($friendUsers->count() == 0) {
            return $items;
        }

        $userIds = kg_array_column($friendUsers->toArray(), 'friend_id');

        $users = $userRepo->findByIds($userIds);

        $userMappings = [];

        foreach ($users as $user) {
            $userMappings[$user->id] = [
                'id' => $user->id,
                'username' => $user->name,
                'avatar' => $user->avatar,
                'sign' => $user->im['sign'] ?? '',
                'status' => $user->im['online']['status'] ?? 'offline',
            ];
        }

        foreach ($items as $key => $item) {
            foreach ($friendUsers as $friendUser) {
                $friend = $userMappings[$friendUser->friend_id];
                if ($item['id'] == $friendUser->group_id) {
                    $friend['msg_count'] = $friendUser->msg_count;
                    $items[$key]['list'][] = $friend;
                } else {
                    $items[0]['list'][] = $friend;
                }
            }
        }

        return $items;
    }

    protected function handleGroupList(UserModel $user)
    {
        $userRepo = new UserRepo();

        $groups = $userRepo->findImGroups($user->id);

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

    protected function handleChatMessagePager($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $messages = $pager->items->toArray();

        $builder = new ImMessageListBuilder();

        $senders = $builder->getSenders($messages);

        $items = [];

        foreach ($messages as $message) {
            $sender = $senders[$message['sender_id']] ?? new \stdClass();
            $items[] = [
                'id' => $message['id'],
                'content' => $message['content'],
                'timestamp' => $message['create_time'] * 1000,
                'user' => $sender,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

    protected function handleUserPager($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $users = $pager->items->toArray();

        $baseUrl = kg_ci_base_url();

        $items = [];

        foreach ($users as $user) {
            $user['avatar'] = $baseUrl . $user['avatar'];
            $items[] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'avatar' => $user['avatar'],
                'about' => $user['about'],
                'vip' => $user['vip'],
            ];
        }

        $pager->items = $items;

        return $pager;
    }

    protected function handleGroupPager($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $groups = $pager->items->toArray();

        $baseUrl = kg_ci_base_url();

        $items = [];

        foreach ($groups as $group) {
            $group['avatar'] = $baseUrl . $group['avatar'];
            $items[] = [
                'id' => $group['id'],
                'name' => $group['name'],
                'avatar' => $group['avatar'],
                'about' => $group['about'],
            ];
        }

        $pager->items = $items;

        return $pager;
    }

    protected function getGroupName($groupId)
    {
        return "group_{$groupId}";
    }

    protected function getRegisterAddress()
    {
        return '127.0.0.1:1238';
    }

}
