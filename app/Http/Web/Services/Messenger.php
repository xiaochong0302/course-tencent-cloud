<?php

namespace App\Http\Web\Services;

use App\Builders\ImMessageList as ImMessageListBuilder;
use App\Caches\ImHotGroupList as ImHotGroupListCache;
use App\Caches\ImHotUserList as ImHotUserListCache;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\ImFriendMessage as ImFriendMessageModel;
use App\Models\ImFriendUser as ImFriendUserModel;
use App\Models\ImGroupMessage as ImGroupMessageModel;
use App\Models\ImSystemMessage as ImSystemMessageModel;
use App\Models\User as UserModel;
use App\Repos\ImChatGroup as ImChatGroupRepo;
use App\Repos\ImFriendMessage as ImFriendMessageRepo;
use App\Repos\ImFriendUser as ImFriendUserRepo;
use App\Repos\ImGroupMessage as ImGroupMessageRepo;
use App\Repos\ImSystemMessage as ImSystemMessageRepo;
use App\Repos\User as UserRepo;
use App\Validators\ImChatGroup as ImChatGroupValidator;
use App\Validators\ImFriendUser as ImFriendUserValidator;
use App\Validators\ImMessage as ImMessageValidator;
use App\Validators\User as UserValidator;
use GatewayClient\Gateway;

class Messenger extends Service
{

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

        $groupRepo = new ImChatGroupRepo();

        $pager = $groupRepo->paginate($params, $sort, $page, $limit);

        return $this->handleGroupPager($pager);
    }

    public function getHotUsers()
    {
        $cache = new ImHotUserListCache();

        $items = $cache->get();

        $pager = new \stdClass();

        $pager->total_items = count($items);
        $pager->total_pages = 1;
        $pager->items = $items;

        return $pager;
    }

    public function getHotGroups()
    {
        $cache = new ImHotGroupListCache();

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

        $validator = new ImChatGroupValidator();

        $group = $validator->checkGroupCache($id);

        $groupRepo = new ImChatGroupRepo();

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

    public function getUnreadSystemMessagesCount()
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

        /**
         * @todo 发送未读消息
         */

        /**
         * @todo 发送盒子消息
         */
    }

    public function sendMessage()
    {
        $user = $this->getLoginUser();

        $from = $this->request->getPost('from');
        $to = $this->request->getPost('to');

        $validator = new ImMessageValidator();

        $validator->checkReceiver($to['id'], $to['type']);

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
            $content['id'] = $to['id'];
        }

        $content = json_encode([
            'type' => 'show_chat_msg',
            'message' => $message,
        ]);

        Gateway::$registerAddress = '127.0.0.1:1238';

        if ($to['type'] == 'friend') {

            /**
             * 不推送自己给自己发送的消息
             */
            if ($user->id != $to['id']) {

                $online = Gateway::isUidOnline($to['id']);

                $messageModel = new ImFriendMessageModel();

                $messageModel->sender_id = $from['id'];
                $messageModel->receiver_id = $to['id'];
                $messageModel->content = $from['content'];
                $messageModel->viewed = $online ? 1 : 0;

                $messageModel->create();

                if ($online) {
                    Gateway::sendToUid($to['id'], $content);
                }
            }

        } elseif ($to['type'] == 'group') {

            $messageModel = new ImGroupMessageModel();

            $messageModel->sender_id = $from['id'];
            $messageModel->group_id = $to['id'];
            $messageModel->content = $from['content'];

            $messageModel->create();

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

    public function markSystemMessagesAsRead()
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

    public function updateSignature()
    {
        $sign = $this->request->getPost('sign');

        $user = $this->getLoginUser();

        $validator = new UserValidator();

        $validator->checkSign($sign);

        $user->update(['sign' => $sign]);

        return $user;
    }

    public function applyFriend()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $validator = new ImFriendUserValidator();

        $friend = $validator->checkFriend($post['friend_id']);
        $group = $validator->checkGroup($post['group_id']);
        $remark = $validator->checkRemark($post['remark']);

        $validator->checkIfSelfApply($user->id, $friend->id);
        $validator->checkIfJoined($user->id, $friend->id);
        $validator->checkIfBlocked($user->id, $friend->id);

        $friendUserRepo = new ImFriendUserRepo();

        $friendUser = $friendUserRepo->findFriendUser($user->id, $friend->id);

        if (!$friendUser) {
            $model = new ImFriendUserModel();
            $model->user_id = $user->id;
            $model->friend_id = $friend->id;
            $model->group_id = $group->id;
            $model->create();
        } else {
            $friendUser->group_id = $group->id;
            $friendUser->update();
        }

        $this->handleApplyFriendNotice($user, $friend, $remark);
    }

    public function acceptFriend()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $validator = new ImFriendUserValidator();

        $friend = $validator->checkFriend($post['friend_id']);
        $group = $validator->checkGroup($post['group_id']);

        $friendUserRepo = new ImFriendUserRepo();

        $friendUser = $friendUserRepo->findFriendUser($user->id, $friend->id);

        if (!$friendUser) {
            $model = new ImFriendUserModel();
            $model->user_id = $user->id;
            $model->friend_id = $friend->id;
            $model->group_id = $group->id;
            $model->create();
        }

        $this->handleAcceptFriendNotice();
    }

    public function refuseFriend()
    {
        $friendId = $this->request->getPost('friend_id');

        $user = $this->getLoginUser();

        $userValidator = new UserValidator();

        $friend = $userValidator->checkUser($friendId);

        /**
         * @todo 向对方发拒绝添加好友的系统消息
         */
    }

    public function applyGroup()
    {
    }

    public function acceptGroup()
    {
    }

    public function refuseGroup()
    {
    }

    protected function handleFriendList($userId)
    {
        $userRepo = new UserRepo();

        $friendGroups = $userRepo->findImFriendGroups($userId);
        $friendUsers = $userRepo->findImFriendUsers($userId);

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
                'sign' => $user->sign,
                'status' => 'online',
            ];
        }

        foreach ($items as $key => $item) {
            foreach ($friendUsers as $friendUser) {
                $userId = $friendUser->friend_id;
                if ($item['id'] == $friendUser->group_id) {
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
                'location' => $user['location'],
                'gender' => $user['gender'],
                'vip' => $user['vip'],
                'follower_count' => $user['follower_count'],
                'following_count' => $user['following_count'],
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
                'user_count' => $group['user_count'],
            ];
        }

        $pager->items = $items;

        return $pager;
    }

    protected function handleApplyFriendNotice(UserModel $sender, UserModel $receiver, $remark)
    {
        $userRepo = new UserRepo();

        $itemType = ImSystemMessageModel::TYPE_FRIEND_REQUEST;

        $message = $userRepo->findImSystemMessage($receiver->id, $itemType);

        if ($message) {

            /**
             * 请求未过期
             */
            if (time() - $message->create_time < 3 * 86400) {
                return;
            }

            if ($message->item_type['accepted'] == 1) {
                return;
            }
        }

        $senderInfo = [
            'id' => $sender->id,
            'name' => $sender->name,
            'avatar' => $sender->avatar,
        ];

        $sysMsgModel = new ImSystemMessageModel();

        $sysMsgModel->sender_id = $sender->id;
        $sysMsgModel->receiver_id = $receiver->id;
        $sysMsgModel->item_type = ImSystemMessageModel::TYPE_FRIEND_REQUEST;
        $sysMsgModel->item_info = [
            'sender' => $senderInfo,
            'remark' => $remark,
            'accepted' => 0,
        ];

        $sysMsgModel->create();

        Gateway::$registerAddress = '127.0.0.1:1238';

        $online = Gateway::isUidOnline($receiver->id);

        if ($online) {

            $content = kg_json_encode(['type' => 'show_msg_box']);

            Gateway::sendToUid($receiver->id, $content);
        }
    }

    protected function handleAcceptFriendNotice(UserModel $user, UserModel $friend)
    {
        $sysMsgModel = new ImSystemMessageModel();

        $sysMsgModel->user_id = $friend->id;
        $sysMsgModel->item_id = $user->id;
        $sysMsgModel->item_type = ImSystemMessageModel::TYPE_FRIEND_APPROVED;
        $sysMsgModel->item_info = [
            'user' => ['id' => $user->id, 'name' => $user->name, 'avatar' => $user->avatar],
        ];

        $sysMsgModel->create();

        Gateway::$registerAddress = '127.0.0.1:1238';

        $online = Gateway::isUidOnline($friend->id);

        if ($online) {

            $userRepo = new UserRepo();

            $msgCount = $userRepo->countUnreadImSystemMessages($friend->id);

            $message = kg_json_encode([
                'type' => 'show_msg_box',
                'content' => ['msg_count' => $msgCount],
            ]);

            Gateway::sendToUid($friend->id, $message);
        }
    }

    protected function handleRefuseFriendNotice()
    {
    }

    protected function getGroupName($groupId)
    {
        return "group_{$groupId}";
    }

}
