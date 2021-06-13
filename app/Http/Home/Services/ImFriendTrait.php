<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Services;

use App\Models\ImFriendGroup as ImFriendGroupModel;
use App\Models\ImFriendUser as ImFriendUserModel;
use App\Models\ImNotice as ImNoticeModel;
use App\Models\ImUser as ImUserModel;
use App\Repos\ImFriendUser as ImFriendUserRepo;
use App\Repos\ImUser as ImUserRepo;
use App\Validators\ImFriendUser as ImFriendUserValidator;
use App\Validators\ImNotice as ImNoticeValidator;
use GatewayClient\Gateway;
use Phalcon\Di;
use Phalcon\Http\Request;

Trait ImFriendTrait
{

    public function applyFriend()
    {
        /**
         * @var Request $request
         */
        $request = Di::getDefault()->get('request');

        $loginUser = $this->getLoginUser();

        $user = $this->getImUser($loginUser->id);

        $post = $request->getPost();

        $validator = new ImFriendUserValidator();

        $friend = $validator->checkFriend($post['friend_id']);
        $group = $validator->checkGroup($post['group_id']);
        $remark = $validator->checkRemark($post['remark']);

        $validator->checkIfSelfApply($user->id, $friend->id);
        $validator->checkIfJoined($user->id, $friend->id);

        $this->handleApplyFriendNotice($user, $friend, $group, $remark);
    }

    public function acceptFriend()
    {
        /**
         * @var Request $request
         */
        $request = Di::getDefault()->get('request');

        $loginUser = $this->getLoginUser();

        $user = $this->getImUser($loginUser->id);

        $noticeId = $request->getPost('notice_id');
        $groupId = $request->getPost('group_id');

        $validator = new ImFriendUserValidator();

        $group = $validator->checkGroup($groupId);

        $validator = new ImNoticeValidator();

        $notice = $validator->checkNotice($noticeId);

        if ($notice->item_type != ImNoticeModel::TYPE_FRIEND_REQUEST) {
            return;
        }

        $sender = $this->getImUser($notice->sender_id);

        $friendUserRepo = new ImFriendUserRepo();

        $friendUser = $friendUserRepo->findFriendUser($user->id, $sender->id);

        if (!$friendUser) {

            $friendUserModel = new ImFriendUserModel();

            $friendUserModel->create([
                'user_id' => $user->id,
                'friend_id' => $sender->id,
                'group_id' => $group->id,
            ]);

            $this->incrUserFriendCount($user);
        }

        $friendUser = $friendUserRepo->findFriendUser($sender->id, $user->id);

        $groupId = $notice->item_info['group']['id'] ?: 0;

        if (!$friendUser) {

            $friendUserModel = new ImFriendUserModel();

            $friendUserModel->create([
                'user_id' => $sender->id,
                'friend_id' => $user->id,
                'group_id' => $groupId,
            ]);

            $this->incrUserFriendCount($sender);
        }

        $itemInfo = $notice->item_info;

        $itemInfo['status'] = ImNoticeModel::REQUEST_ACCEPTED;

        $notice->update(['item_info' => $itemInfo]);

        $this->handleAcceptFriendNotice($user, $sender, $notice);
    }

    public function refuseFriend()
    {
        /**
         * @var Request $request
         */
        $request = Di::getDefault()->get('request');

        $loginUser = $this->getLoginUser();

        $user = $this->getImUser($loginUser->id);

        $noticeId = $request->getPost('notice_id');

        $validator = new ImNoticeValidator();

        $notice = $validator->checkNotice($noticeId);

        if ($notice->item_type != ImNoticeModel::TYPE_FRIEND_REQUEST) {
            return;
        }

        $itemInfo = $notice->item_info;

        $itemInfo['status'] = ImNoticeModel::REQUEST_REFUSED;

        $notice->update(['item_info' => $itemInfo]);

        $sender = $this->getImUser($notice->sender_id);

        $this->handleRefuseFriendNotice($user, $sender);
    }

    public function quitFriend($id)
    {
        $loginUser = $this->getLoginUser();

        $user = $this->getImUser($loginUser->id);

        $validator = new ImFriendUserValidator();

        $friend = $validator->checkFriend($id);

        $friendUser = $validator->checkFriendUser($user->id, $friend->id);

        $friendUser->delete();

        $this->decrUserFriendCount($user);
    }

    protected function handleApplyFriendNotice(ImUserModel $sender, ImUserModel $receiver, ImFriendGroupModel $group, $remark)
    {
        $userRepo = new ImUserRepo();

        $itemType = ImNoticeModel::TYPE_FRIEND_REQUEST;

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
        $noticeModel->item_type = ImNoticeModel::TYPE_FRIEND_REQUEST;
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

    protected function handleAcceptFriendNotice(ImUserModel $sender, ImUserModel $receiver, ImNoticeModel $applyNotice)
    {
        $noticeModel = new ImNoticeModel();

        $noticeModel->sender_id = $sender->id;
        $noticeModel->receiver_id = $receiver->id;
        $noticeModel->item_type = ImNoticeModel::TYPE_FRIEND_ACCEPTED;
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

            /**
             * 上层操作更新了item_info，类型发生了变化，故重新获取
             */
            $applyNotice->afterFetch();

            /**
             * @var array $itemInfo
             */
            $itemInfo = $applyNotice->item_info;

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
        $noticeModel = new ImNoticeModel();

        $noticeModel->sender_id = $sender->id;
        $noticeModel->receiver_id = $receiver->id;
        $noticeModel->item_type = ImNoticeModel::TYPE_FRIEND_REFUSED;
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
