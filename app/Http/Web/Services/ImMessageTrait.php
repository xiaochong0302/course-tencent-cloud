<?php

namespace App\Http\Web\Services;

use App\Builders\ImMessageList as ImMessageListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\ImFriendUser as ImFriendUserModel;
use App\Models\ImGroup as ImGroupModel;
use App\Models\ImMessage as ImMessageModel;
use App\Repos\ImFriendUser as ImFriendUserRepo;
use App\Repos\ImMessage as ImMessageRepo;
use App\Repos\ImUser as ImUserRepo;
use App\Validators\ImFriendUser as ImFriendUserValidator;
use App\Validators\ImGroup as ImGroupValidator;
use App\Validators\ImGroupUser as ImGroupUserValidator;
use App\Validators\ImMessage as ImMessageValidator;
use App\Validators\ImUser as ImUserValidator;
use GatewayClient\Gateway;

/**
 * layim中普通聊天和自定义聊天中接收方用户名使用的字段不一样，也够坑爹的
 * 普通聊天username,自定义聊天name
 */
Trait ImMessageTrait
{

    public function pullUnreadFriendMessages($id)
    {
        $user = $this->getLoginUser();

        $validator = new ImUserValidator();

        $friend = $validator->checkUser($id);

        $userRepo = new ImUserRepo();

        $messages = $userRepo->findUnreadFriendMessages($friend->id, $user->id);

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
                    'id' => $friend->id,
                    'fromid' => $friend->id,
                    'content' => $message->content,
                    'timestamp' => 1000 * $message->create_time,
                    'type' => 'friend',
                    'mine' => false,
                ],
            ]);

            Gateway::sendToUid($user->id, $content);
        }

        $repo = new ImFriendUserRepo();

        $friendUser = $repo->findFriendUser($user->id, $friend->id);

        $friendUser->update(['msg_count' => 0]);
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

        if ($params['type'] == ImMessageModel::TYPE_FRIEND) {

            $chatId = ImMessageModel::getChatId($user->id, $params['id']);

            $where = ['chat_id' => $chatId];

            $messageRepo = new ImMessageRepo();

            $pager = $messageRepo->paginate($where, $sort, $page, $limit);

            return $this->handleChatMessagePager($pager);

        } elseif ($params['type'] == ImMessageModel::TYPE_GROUP) {

            $where = [
                'receiver_type' => $params['type'],
                'receiver_id' => $params['id'],
            ];

            $messageRepo = new ImMessageRepo();

            $pager = $messageRepo->paginate($where, $sort, $page, $limit);

            return $this->handleChatMessagePager($pager);
        }
    }

    public function sendChatMessage($from, $to)
    {
        $validator = new ImMessageValidator();

        $validator->checkIfSelfChat($from['id'], $to['id']);

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

        $content = kg_json_encode([
            'type' => 'show_chat_msg',
            'message' => $message,
        ]);

        Gateway::$registerAddress = $this->getRegisterAddress();

        if ($to['type'] == 'friend') {

            $validator = new ImFriendUserValidator();

            $relation = $validator->checkFriendUser($to['id'], $from['id']);

            $online = Gateway::isUidOnline($to['id']);

            $messageModel = new ImMessageModel();

            $messageModel->create([
                'sender_id' => $from['id'],
                'receiver_id' => $to['id'],
                'receiver_type' => ImMessageModel::TYPE_FRIEND,
                'content' => $from['content'],
                'viewed' => $online ? 1 : 0,
            ]);

            if ($online) {
                Gateway::sendToUid($to['id'], $content);
            } else {
                $this->incrFriendUserMsgCount($relation);
            }

        } elseif ($to['type'] == 'group') {

            $user = $this->getLoginUser();

            $validator = new ImGroupValidator();

            $group = $validator->checkGroup($to['id']);

            $validator = new ImGroupUserValidator();

            $validator->checkGroupUser($group->id, $user->id);

            $messageModel = new ImMessageModel();

            $messageModel->create([
                'sender_id' => $from['id'],
                'receiver_id' => $to['id'],
                'receiver_type' => ImMessageModel::TYPE_GROUP,
                'content' => $from['content'],
            ]);

            $this->incrGroupMsgCount($group);

            $excludeClientId = null;

            /**
             * 不推送自己在群组中发的消息
             */
            if ($user->id == $from['id']) {
                $excludeClientId = Gateway::getClientIdByUid($user->id);
            }

            $groupName = $this->getGroupName($group->id);

            Gateway::sendToGroup($groupName, $content, $excludeClientId);
        }
    }

    protected function sendCsMessage($from, $to)
    {
        $validator = new ImMessageValidator();

        $validator->checkIfSelfChat($from['id'], $to['id']);

        $sender = $this->getImUser($from['id']);
        $receiver = $this->getImUser($to['id']);

        $friendUserRepo = new ImFriendUserRepo();

        $friendUser = $friendUserRepo->findFriendUser($sender->id, $receiver->id);

        if (!$friendUser) {

            $friendUserModel = new ImFriendUserModel();

            $friendUserModel->create([
                'user_id' => $sender->id,
                'friend_id' => $receiver->id,
            ]);

            $this->incrUserFriendCount($sender);
        }

        $friendUser = $friendUserRepo->findFriendUser($receiver->id, $sender->id);

        if (!$friendUser) {

            $friendUserModel = new ImFriendUserModel();

            $friendUserModel->create([
                'user_id' => $receiver->id,
                'friend_id' => $sender->id,
            ]);

            $this->incrUserFriendCount($receiver);
        }

        /**
         * 统一普通聊天和自定义聊天的用户名字段
         */
        $to['username'] = $to['name'];

        unset($to['name']);

        $this->sendChatMessage($from, $to);
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

    protected function incrFriendUserMsgCount(ImFriendUserModel $friendUser)
    {
        $friendUser->msg_count += 1;

        $friendUser->update();
    }

    protected function incrGroupMsgCount(ImGroupModel $group)
    {
        $group->msg_count += 1;

        $group->update();
    }

}