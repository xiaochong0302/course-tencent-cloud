<?php

namespace App\Http\Web\Services;

use App\Builders\ImMessageList as ImMessageListBuilder;
use App\Library\Paginator\Query as PagerQuery;
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

    public function pullUnreadFriendMessages()
    {
        $user = $this->getLoginUser();

        $id = $this->request->getQuery('id');

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

    public function sendChatMessage()
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

        $content = kg_json_encode([
            'type' => 'show_chat_msg',
            'message' => $message,
        ]);

        Gateway::$registerAddress = $this->getRegisterAddress();

        if ($to['type'] == ImMessageModel::TYPE_FRIEND) {

            $validator = new ImFriendUserValidator();

            $relation = $validator->checkFriendUser($to['id'], $user->id);

            $online = Gateway::isUidOnline($to['id']);

            $messageModel = new ImMessageModel();

            $messageModel->create([
                'sender_id' => $from['id'],
                'receiver_id' => $to['id'],
                'receiver_type' => $to['type'],
                'content' => $from['content'],
                'viewed' => $online ? 1 : 0,
            ]);

            if ($online) {
                Gateway::sendToUid($to['id'], $content);
            } else {
                $this->incrFriendUserMsgCount($relation);
            }

        } elseif ($to['type'] == ImMessageModel::TYPE_GROUP) {

            $validator = new ImGroupValidator();

            $group = $validator->checkGroup($to['id']);

            $validator = new ImGroupUserValidator();

            $validator->checkGroupUser($group->id, $user->id);

            $messageModel = new ImMessageModel();

            $messageModel->create([
                'sender_id' => $from['id'],
                'receiver_id' => $to['id'],
                'receiver_type' => $to['type'],
                'content' => $from['content'],
            ]);

            $this->incrGroupMessageCount($group);

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

    public function sendCsMessage()
    {
        $from = $this->request->getPost('from');
        $to = $this->request->getPost('to');

        $validator = new ImMessageValidator();

        $from['content'] = $validator->checkContent($from['content']);

        if ($to['id'] > 0) {
            $this->sendCsUserMessage($from, $to);
        } else {
            $this->sendCsRobotMessage($from, $to);
        }
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

    /**
     * 向客服发送消息，建立临时好友关系
     *
     * @param array $from
     * @param array $to
     */
    protected function sendCsUserMessage($from, $to)
    {
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

        $content = kg_json_encode([
            'type' => 'show_cs_msg',
            'message' => $message,
        ]);

        Gateway::$registerAddress = $this->getRegisterAddress();

        $online = Gateway::isUidOnline($to['id']);

        $messageModel = new ImMessageModel();

        $messageModel->create([
            'sender_id' => $from['id'],
            'receiver_id' => $to['id'],
            'receiver_type' => $to['type'],
            'content' => $from['content'],
            'viewed' => $online ? 1 : 0,
        ]);

        if ($online) {
            Gateway::sendToUid($to['id'], $content);
        }
    }

    /**
     * 向机器人发送消息，机器人自动应答
     *
     * @param array $from
     * @param array $to
     */
    protected function sendCsRobotMessage($from, $to)
    {
        /**
         * @todo 从腾讯平台获取应答内容
         */
        $content = '不知道你在说什么...';

        $message = [
            'username' => $to['name'],
            'avatar' => $to['avatar'],
            'content' => $content,
            'fromid' => $to['id'],
            'id' => $to['id'],
            'type' => $to['type'],
            'timestamp' => 1000 * time(),
            'mine' => false,
        ];

        $content = kg_json_encode([
            'type' => 'show_cs_msg',
            'message' => $message,
        ]);

        Gateway::$registerAddress = $this->getRegisterAddress();

        Gateway::sendToUid($from['id'], $content);
    }

}