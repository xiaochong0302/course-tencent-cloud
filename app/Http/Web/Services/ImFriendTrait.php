<?php

namespace App\Http\Web\Services;

use App\Models\ImFriendGroup as ImFriendGroupModel;
use App\Models\ImFriendUser as ImFriendUserModel;
use App\Models\ImSystemMessage as ImSystemMessageModel;
use App\Models\ImUser as ImUserModel;
use App\Repos\ImFriendUser as ImFriendUserRepo;
use App\Repos\ImUser as ImUserRepo;
use App\Validators\ImFriendUser as ImFriendUserValidator;
use App\Validators\ImMessage as ImMessageValidator;
use GatewayClient\Gateway;

Trait ImFriendTrait
{

    public function applyFriend()
    {
        $loginUser = $this->getLoginUser();

        $user = $this->getImUser($loginUser->id);

        $post = $this->request->getPost();

        $validator = new ImFriendUserValidator();

        $friend = $validator->checkFriend($post['friend_id']);
        $group = $validator->checkGroup($post['group_id']);
        $remark = $validator->checkRemark($post['remark']);

        $validator->checkIfSelf($user->id, $friend->id);
        $validator->checkIfJoined($user->id, $friend->id);
        $validator->checkIfBlocked($user->id, $friend->id);

        $this->handleApplyFriendNotice($user, $friend, $group, $remark);
    }

    public function acceptFriend()
    {
        $loginUser = $this->getLoginUser();

        $user = $this->getImUser($loginUser->id);

        $messageId = $this->request->getPost('message_id');
        $groupId = $this->request->getPost('group_id');

        $validator = new ImFriendUserValidator();

        $validator->checkGroup($groupId);

        $validator = new ImMessageValidator();

        $message = $validator->checkMessage($messageId, 'system');

        if ($message->item_type != ImSystemMessageModel::TYPE_FRIEND_REQUEST) {
            return;
        }

        $sender = $this->getImUser($message->sender_id);

        $friendUserRepo = new ImFriendUserRepo();

        $friendUser = $friendUserRepo->findFriendUser($user->id, $sender->id);

        if (!$friendUser) {

            $friendUserModel = new ImFriendUserModel();

            $friendUserModel->create([
                'user_id' => $user->id,
                'friend_id' => $sender->id,
                'group_id' => $groupId,
            ]);

            $this->incrUserFriendCount($user);
        }

        $friendUser = $friendUserRepo->findFriendUser($sender->id, $user->id);

        $groupId = $message->item_info['group']['id'] ?: 0;

        if (!$friendUser) {

            $friendUserModel = new ImFriendUserModel();

            $friendUserModel->create([
                'user_id' => $sender->id,
                'friend_id' => $user->id,
                'group_id' => $groupId,
            ]);

            $this->incrUserFriendCount($sender);
        }

        $itemInfo = $message->item_info;

        $itemInfo['status'] = ImSystemMessageModel::REQUEST_ACCEPTED;

        $message->update(['item_info' => $itemInfo]);

        $this->handleAcceptFriendNotice($user, $sender, $message);
    }

    public function refuseFriend()
    {
        $loginUser = $this->getLoginUser();

        $user = $this->getImUser($loginUser->id);

        $messageId = $this->request->getPost('message_id');

        $validator = new ImMessageValidator();

        $message = $validator->checkMessage($messageId, 'system');

        if ($message->item_type != ImSystemMessageModel::TYPE_FRIEND_REQUEST) {
            return;
        }

        $itemInfo = $message->item_info;

        $itemInfo['status'] = ImSystemMessageModel::REQUEST_REFUSED;

        $message->update(['item_info' => $itemInfo]);

        $sender = $this->getImUser($message->sender_id);

        $this->handleRefuseFriendNotice($user, $sender);
    }

    protected function handleApplyFriendNotice(ImUserModel $sender, ImUserModel $receiver, ImFriendGroupModel $group, $remark)
    {
        $userRepo = new ImUserRepo();

        $itemType = ImSystemMessageModel::TYPE_FRIEND_REQUEST;

        $message = $userRepo->findSystemMessage($receiver->id, $itemType);

        if ($message) {
            $expired = time() - $message->create_time > 7 * 86400;
            $pending = $message->item_info['status'] == ImSystemMessageModel::REQUEST_PENDING;
            if (!$expired && $pending) {
                return;
            }
        }

        $sysMsgModel = new ImSystemMessageModel();

        $sysMsgModel->sender_id = $sender->id;
        $sysMsgModel->receiver_id = $receiver->id;
        $sysMsgModel->item_type = ImSystemMessageModel::TYPE_FRIEND_REQUEST;
        $sysMsgModel->item_info = [
            'sender' => [
                'id' => $sender->id,
                'name' => $sender->name,
                'avatar' => $sender->avatar,
            ],
            'group' => [
                'id' => $group->id,
                'name' => $group->name,
            ],
            'remark' => $remark,
            'status' => ImSystemMessageModel::REQUEST_PENDING,
        ];

        $sysMsgModel->create();

        Gateway::$registerAddress = $this->getRegisterAddress();

        $online = Gateway::isUidOnline($receiver->id);

        if ($online) {
            $content = kg_json_encode(['type' => 'refresh_msg_box']);
            Gateway::sendToUid($receiver->id, $content);
        }
    }

    protected function handleAcceptFriendNotice(ImUserModel $sender, ImUserModel $receiver, ImSystemMessageModel $applyMessage)
    {
        $sysMsgModel = new ImSystemMessageModel();

        $sysMsgModel->sender_id = $sender->id;
        $sysMsgModel->receiver_id = $receiver->id;
        $sysMsgModel->item_type = ImSystemMessageModel::TYPE_FRIEND_ACCEPTED;
        $sysMsgModel->item_info = [
            'sender' => [
                'id' => $sender->id,
                'name' => $sender->name,
                'avatar' => $sender->avatar,
            ]
        ];

        $sysMsgModel->create();

        Gateway::$registerAddress = $this->getRegisterAddress();

        $online = Gateway::isUidOnline($receiver->id);

        if ($online) {

            /**
             * 上层操作更新了item_info，类型发生了变化，故重新获取
             */
            $applyMessage->afterFetch();

            /**
             * @var array $itemInfo
             */
            $itemInfo = $applyMessage->item_info;

            $content = kg_json_encode([
                'type' => 'friend_accepted',
                'friend' => [
                    'id' => $sender->id,
                    'name' => $sender->name,
                    'avatar' => $sender->avatar,
                ],
                'group' => [
                    'id' => $itemInfo['group']['id'],
                    'name' => $itemInfo['group']['name'],
                ],
            ]);

            Gateway::sendToUid($receiver->id, $content);
        }
    }

    protected function handleRefuseFriendNotice(ImUserModel $sender, ImUserModel $receiver)
    {
        $sysMsgModel = new ImSystemMessageModel();

        $sysMsgModel->sender_id = $sender->id;
        $sysMsgModel->receiver_id = $receiver->id;
        $sysMsgModel->item_type = ImSystemMessageModel::TYPE_FRIEND_REFUSED;
        $sysMsgModel->item_info = [
            'sender' => [
                'id' => $sender->id,
                'name' => $sender->name,
                'avatar' => $sender->avatar,
            ]
        ];

        $sysMsgModel->create();

        Gateway::$registerAddress = $this->getRegisterAddress();

        $online = Gateway::isUidOnline($receiver->id);

        if ($online) {
            $content = kg_json_encode(['type' => 'refresh_msg_box']);
            Gateway::sendToUid($receiver->id, $content);
        }
    }

    protected function incrUserFriendCount(ImUserModel $user)
    {
        $user->friend_count += 1;
        $user->update();
    }

    protected function decrUserFriendCount(ImUserModel $user)
    {
        if ($user->friend_count > 0) {
            $user->friend_count -= 1;
            $user->update();
        }
    }

}
