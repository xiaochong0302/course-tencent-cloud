<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Services;

use App\Models\ImGroup as ImGroupModel;
use App\Models\ImGroupUser as ImGroupUserModel;
use App\Models\ImNotice as ImNoticeModel;
use App\Models\ImUser as ImUserModel;
use App\Repos\ImGroup as ImGroupRepo;
use App\Repos\ImGroupUser as ImGroupUserRepo;
use App\Repos\ImUser as ImUserRepo;
use App\Validators\ImGroup as ImGroupValidator;
use App\Validators\ImGroupUser as ImGroupUserValidator;
use App\Validators\ImNotice as ImNoticeValidator;
use GatewayClient\Gateway;
use Phalcon\Di;
use Phalcon\Http\Request;

Trait ImGroupTrait
{

    public function applyGroup()
    {
        /**
         * @var Request $request
         */
        $request = Di::getDefault()->get('request');

        $loginUser = $this->getLoginUser();

        $user = $this->getImUser($loginUser->id);

        $validator = new ImGroupUserValidator();

        $post = $request->getPost();

        $group = $validator->checkGroup($post['group_id']);
        $remark = $validator->checkRemark($post['remark']);

        $validator->checkIfJoined($group->id, $user->id);
        $validator->checkIfAllowJoin($group->id, $user->id);

        $this->handleApplyGroupNotice($user, $group, $remark);
    }

    public function acceptGroup()
    {
        /**
         * @var Request $request
         */
        $request = Di::getDefault()->get('request');

        $loginUser = $this->getLoginUser();

        $user = $this->getImUser($loginUser->id);

        $noticeId = $request->getPost('notice_id', 'int');

        $validator = new ImNoticeValidator();

        $notice = $validator->checkNotice($noticeId);

        if ($notice->item_type != ImNoticeModel::TYPE_GROUP_REQUEST) return;

        $groupId = $notice->item_info['group']['id'] ?: 0;

        $validator = new ImGroupValidator();

        $group = $validator->checkGroup($groupId);

        $validator->checkOwner($user->id, $group->owner_id);

        $applicant = $this->getImUser($notice->sender_id);

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

        $itemInfo = $notice->item_info;

        $itemInfo['status'] = ImNoticeModel::REQUEST_ACCEPTED;

        $notice->update(['item_info' => $itemInfo]);

        $this->handleAcceptGroupNotice($user, $applicant, $group);

        $this->handleNewGroupUserNotice($applicant, $group);
    }

    public function refuseGroup()
    {
        /**
         * @var Request $request
         */
        $request = Di::getDefault()->get('request');

        $loginUser = $this->getLoginUser();

        $user = $this->getImUser($loginUser->id);

        $noticeId = $request->getPost('notice_id', 'int');

        $validator = new ImNoticeValidator();

        $notice = $validator->checkNotice($noticeId);

        if ($notice->item_type != ImNoticeModel::TYPE_GROUP_REQUEST) return;

        $groupId = $notice->item_info['group']['id'] ?: 0;

        $validator = new ImGroupValidator();

        $group = $validator->checkGroup($groupId);

        $validator->checkOwner($user->id, $group->owner_id);

        $itemInfo = $notice->item_info;

        $itemInfo['status'] = ImNoticeModel::REQUEST_REFUSED;

        $notice->update(['item_info' => $itemInfo]);

        $sender = $this->getImUser($notice->sender_id);

        $this->handleRefuseGroupNotice($user, $sender);
    }

    public function quitGroup($id)
    {
        $loginUser = $this->getLoginUser();

        $user = $this->getImUser($loginUser->id);

        $validator = new ImGroupUserValidator();

        $group = $validator->checkGroup($id);

        $groupUser = $validator->checkGroupUser($group->id, $user->id);

        $validator->checkIfAllowDelete($group->id, $user->id);

        $groupUser->delete();

        $this->decrGroupUserCount($group);

        $this->decrUserGroupCount($user);
    }

    protected function handleApplyGroupNotice(ImUserModel $sender, ImGroupModel $group, $remark)
    {
        $userRepo = new ImUserRepo();

        $receiver = $userRepo->findById($group->owner_id);

        $itemType = ImNoticeModel::TYPE_GROUP_REQUEST;

        $notice = $userRepo->findNotice($receiver->id, $itemType);

        if ($notice) {
            $expired = time() - $notice->create_time > 7 * 86400;
            $pending = $notice->item_info['status'] == ImNoticeModel::REQUEST_PENDING;
            if (!$expired && $pending) {
                return;
            }
        }

        $noticeModel = new ImNoticeModel();

        $noticeModel->sender_id = $sender->id;
        $noticeModel->receiver_id = $receiver->id;
        $noticeModel->item_type = ImNoticeModel::TYPE_GROUP_REQUEST;
        $noticeModel->item_info = [
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
            'status' => ImNoticeModel::REQUEST_PENDING,
        ];

        $noticeModel->create();

        Gateway::$registerAddress = $this->getRegisterAddress();

        $online = Gateway::isUidOnline($receiver->id);

        if ($online) {

            $content = kg_json_encode(['type' => 'refresh_msg_box']);

            Gateway::sendToUid($receiver->id, $content);
        }
    }

    protected function handleAcceptGroupNotice(ImUserModel $sender, ImUserModel $receiver, ImGroupModel $group)
    {
        $noticeModel = new ImNoticeModel();

        $noticeModel->sender_id = $sender->id;
        $noticeModel->receiver_id = $receiver->id;
        $noticeModel->item_type = ImNoticeModel::TYPE_GROUP_ACCEPTED;
        $noticeModel->item_info = [
            'sender' => [
                'id' => $sender->id,
                'name' => $sender->name,
                'avatar' => $sender->avatar,
            ]
        ];

        $noticeModel->create();

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
        $noticeModel = new ImNoticeModel();

        $noticeModel->sender_id = $sender->id;
        $noticeModel->receiver_id = $receiver->id;
        $noticeModel->item_type = ImNoticeModel::TYPE_GROUP_REFUSED;
        $noticeModel->item_info = [
            'sender' => [
                'id' => $sender->id,
                'name' => $sender->name,
                'avatar' => $sender->avatar,
            ]
        ];

        $noticeModel->create();

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

        if ($users->count() == 0) return;

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
