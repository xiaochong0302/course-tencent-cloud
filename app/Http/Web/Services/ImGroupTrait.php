<?php

namespace App\Http\Web\Services;

use App\Models\ImChatGroup as ImChatGroupModel;
use App\Models\ImChatGroupUser as ImChatGroupUserModel;
use App\Models\ImSystemMessage as ImSystemMessageModel;
use App\Models\User as UserModel;
use App\Repos\ImChatGroup as ImChatGroupRepo;
use App\Repos\ImChatGroupUser as ImChatGroupUserRepo;
use App\Repos\User as UserRepo;
use App\Validators\ImChatGroup as ImChatGroupValidator;
use App\Validators\ImChatGroupUser as ImChatGroupUserValidator;
use App\Validators\ImMessage as ImMessageValidator;
use GatewayClient\Gateway;

Trait ImGroupTrait
{

    public function applyGroup()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $validator = new ImChatGroupUserValidator();

        $group = $validator->checkGroup($post['group_id']);
        $remark = $validator->checkRemark($post['remark']);

        $validator->checkIfJoined($user->id, $group->id);
        $validator->checkIfBlocked($user->id, $group->id);

        $this->handleApplyGroupNotice($user, $group, $remark);
    }

    public function acceptGroup()
    {
        $user = $this->getLoginUser();

        $messageId = $this->request->getPost('message_id');

        $validator = new ImMessageValidator();

        $message = $validator->checkMessage($messageId, 'system');

        if ($message->item_type != ImSystemMessageModel::TYPE_GROUP_REQUEST) {
            return;
        }

        $groupId = $message->item_info['group']['id'] ?: 0;

        $validator = new ImChatGroupValidator();

        $group = $validator->checkGroup($groupId);

        $validator->checkOwner($user->id, $group->user_id);

        $userRepo = new UserRepo();

        $applicant = $userRepo->findById($message->sender_id);

        $groupUserRepo = new ImChatGroupUserRepo();

        $groupUser = $groupUserRepo->findGroupUser($group->id, $applicant->id);

        if (!$groupUser) {
            $groupUserModel = new ImChatGroupUserModel();
            $groupUserModel->create([
                'group_id' => $group->id,
                'user_id' => $applicant->id,
            ]);
        }

        $itemInfo = $message->item_info;
        $itemInfo['status'] = ImSystemMessageModel::REQUEST_ACCEPTED;
        $message->update(['item_info' => $itemInfo]);

        $this->handleAcceptGroupNotice($user, $applicant, $group);

        $this->handleNewGroupUserNotice($applicant, $group);
    }

    public function refuseGroup()
    {
        $user = $this->getLoginUser();

        $messageId = $this->request->getPost('message_id');

        $validator = new ImMessageValidator();

        $message = $validator->checkMessage($messageId, 'system');

        if ($message->item_type != ImSystemMessageModel::TYPE_GROUP_REQUEST) {
            return;
        }

        $groupId = $message->item_info['group']['id'] ?: 0;

        $validator = new ImChatGroupValidator();

        $group = $validator->checkGroup($groupId);

        $validator->checkOwner($user->id, $group->user_id);

        $itemInfo = $message->item_info;
        $itemInfo['status'] = ImSystemMessageModel::REQUEST_REFUSED;
        $message->update(['item_info' => $itemInfo]);

        $userRepo = new UserRepo();

        $sender = $userRepo->findById($message->sender_id);

        $this->handleRefuseGroupNotice($user, $sender);
    }

    protected function handleApplyGroupNotice(UserModel $sender, ImChatGroupModel $group, $remark)
    {
        $userRepo = new UserRepo();

        $receiver = $userRepo->findById($group->user_id);

        $itemType = ImSystemMessageModel::TYPE_GROUP_REQUEST;

        $message = $userRepo->findImSystemMessage($receiver->id, $itemType);

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
        $sysMsgModel->item_type = ImSystemMessageModel::TYPE_GROUP_REQUEST;
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

    protected function handleAcceptGroupNotice(UserModel $sender, UserModel $receiver, ImChatGroupModel $group)
    {
        $sysMsgModel = new ImSystemMessageModel();

        $sysMsgModel->sender_id = $sender->id;
        $sysMsgModel->receiver_id = $receiver->id;
        $sysMsgModel->item_type = ImSystemMessageModel::TYPE_GROUP_ACCEPTED;
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

            $content = kg_json_encode([
                'type' => 'group_accepted',
                'group' => [
                    'id' => $group->id,
                    'name' => $group->name,
                    'avatar' => $group->avatar,
                ],
            ]);

            Gateway::sendToUid($receiver->id, $content);
        }
    }

    protected function handleRefuseGroupNotice(UserModel $sender, UserModel $receiver)
    {
        $sysMsgModel = new ImSystemMessageModel();

        $sysMsgModel->sender_id = $sender->id;
        $sysMsgModel->receiver_id = $receiver->id;
        $sysMsgModel->item_type = ImSystemMessageModel::TYPE_GROUP_REFUSED;
        $sysMsgModel->item_info = [
            'sender' => [
                'id' => $sender->id,
                'name' => $sender->name,
                'avatar' => $sender->avatar,
            ]
        ];

        $sysMsgModel->create();

        Gateway::$registerAddress = $this->getRegisterAddress();

        if (Gateway::isUidOnline($receiver->id)) {
            $content = kg_json_encode(['type' => 'refresh_msg_box']);
            Gateway::sendToUid($receiver->id, $content);
        }
    }

    protected function handleNewGroupUserNotice(UserModel $newUser, ImChatGroupModel $group)
    {
        $groupRepo = new ImChatGroupRepo();

        $users = $groupRepo->findGroupUsers($group->id);

        if ($users->count() == 0) {
            return;
        }

        Gateway::$registerAddress = $this->getRegisterAddress();

        foreach ($users as $user) {
            $content = kg_json_encode([
                'type' => 'new_group_user',
                'user' => [
                    'id' => $newUser->id,
                    'name' => $newUser->name,
                    'avatar' => $newUser->avatar,
                ],
                'group' => [
                    'id' => $group->id,
                    'name' => $group->name,
                    'avatar' => $group->avatar,
                ],
            ]);
            if (Gateway::isUidOnline($user->id)) {
                Gateway::sendToUid($user->id, $content);
            }
        }
    }

}
