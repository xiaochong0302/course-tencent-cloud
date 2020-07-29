<?php

namespace App\Http\Web\Services;

use App\Models\ImGroup as ImGroupModel;
use App\Models\ImGroupUser as ImGroupUserModel;
use App\Models\ImSystemMessage as ImSystemMessageModel;
use App\Models\ImUser as ImUserModel;
use App\Repos\ImGroup as ImGroupRepo;
use App\Repos\ImGroupUser as ImGroupUserRepo;
use App\Repos\ImUser as ImUserRepo;
use App\Validators\ImGroup as ImGroupValidator;
use App\Validators\ImGroupUser as ImGroupUserValidator;
use App\Validators\ImMessage as ImMessageValidator;
use GatewayClient\Gateway;

Trait ImGroupTrait
{

    public function applyGroup()
    {
        $loginUser = $this->getLoginUser();

        $user = $this->getImUser($loginUser->id);

        $post = $this->request->getPost();

        $validator = new ImGroupUserValidator();

        $group = $validator->checkGroup($post['group_id']);
        $remark = $validator->checkRemark($post['remark']);

        $validator->checkIfJoined($group->id, $user->id);

        $this->handleApplyGroupNotice($user, $group, $remark);
    }

    public function acceptGroup()
    {
        $loginUser = $this->getLoginUser();

        $user = $this->getImUser($loginUser->id);

        $messageId = $this->request->getPost('message_id');

        $validator = new ImMessageValidator();

        $message = $validator->checkMessage($messageId, 'system');

        if ($message->item_type != ImSystemMessageModel::TYPE_GROUP_REQUEST) {
            return;
        }

        $groupId = $message->item_info['group']['id'] ?: 0;

        $validator = new ImGroupValidator();

        $group = $validator->checkGroup($groupId);

        $validator->checkOwner($user->id, $group->user_id);

        $applicant = $this->getImUser($message->sender_id);

        $groupUserRepo = new ImGroupUserRepo();

        $groupUser = $groupUserRepo->findGroupUser($group->id, $applicant->id);

        if (!$groupUser) {

            $groupUserModel = new ImGroupUserModel();

            $groupUserModel->create([
                'group_id' => $group->id,
                'user_id' => $applicant->id,
            ]);

            $this->incrGroupUserCount($group);

            $this->incrUserGroupCount($applicant);
        }

        $itemInfo = $message->item_info;

        $itemInfo['status'] = ImSystemMessageModel::REQUEST_ACCEPTED;

        $message->update(['item_info' => $itemInfo]);

        $this->handleAcceptGroupNotice($user, $applicant, $group);

        $this->handleNewGroupUserNotice($applicant, $group);
    }

    public function refuseGroup()
    {
        $loginUser = $this->getLoginUser();

        $user = $this->getImUser($loginUser->id);

        $messageId = $this->request->getPost('message_id');

        $validator = new ImMessageValidator();

        $message = $validator->checkMessage($messageId, 'system');

        if ($message->item_type != ImSystemMessageModel::TYPE_GROUP_REQUEST) {
            return;
        }

        $groupId = $message->item_info['group']['id'] ?: 0;

        $validator = new ImGroupValidator();

        $group = $validator->checkGroup($groupId);

        $validator->checkOwner($user->id, $group->user_id);

        $itemInfo = $message->item_info;

        $itemInfo['status'] = ImSystemMessageModel::REQUEST_REFUSED;

        $message->update(['item_info' => $itemInfo]);

        $sender = $this->getImUser($message->sender_id);

        $this->handleRefuseGroupNotice($user, $sender);
    }

    public function quitGroup($id)
    {
        $loginUser = $this->getLoginUser();

        $user = $this->getImUser($loginUser->id);

        $validator = new ImGroupUserValidator();

        $group = $validator->checkGroup($id);

        $groupUser = $validator->checkGroupUser($group->id, $user->id);

        $groupUser->delete();

        $this->decrGroupUserCount($group);

        $this->decrUserGroupCount($user);
    }

    protected function handleApplyGroupNotice(ImUserModel $sender, ImGroupModel $group, $remark)
    {
        $userRepo = new ImUserRepo();

        $receiver = $userRepo->findById($group->user_id);

        $itemType = ImSystemMessageModel::TYPE_GROUP_REQUEST;

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

    protected function handleAcceptGroupNotice(ImUserModel $sender, ImUserModel $receiver, ImGroupModel $group)
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

    protected function handleRefuseGroupNotice(ImUserModel $sender, ImUserModel $receiver)
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

    protected function handleNewGroupUserNotice(ImUserModel $newUser, ImGroupModel $group)
    {
        $groupRepo = new ImGroupRepo();

        $users = $groupRepo->findUsers($group->id);

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

    protected function incrUserGroupCount(ImUserModel $user)
    {
        $user->group_count += 1;
        $user->update();
    }

    protected function decrUserGroupCount(ImUserModel $user)
    {
        if ($user->group_count > 0) {
            $user->group_count -= 1;
            $user->update();
        }
    }

    protected function incrGroupUserCount(ImGroupModel $group)
    {
        $group->user_count += 1;
        $group->update();
    }

    protected function decrGroupUserCount(ImGroupModel $group)
    {
        if ($group->user_count > 0) {
            $group->user_count -= 1;
            $group->update();
        }
    }

}
