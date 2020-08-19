<?php

namespace App\Http\Web\Services;

use App\Caches\Setting as SettingCache;
use App\Models\ImUser as ImUserModel;
use App\Repos\ImGroup as ImGroupRepo;
use App\Repos\ImUser as ImUserRepo;
use App\Validators\ImGroup as ImGroupValidator;
use App\Validators\ImUser as ImUserValidator;
use GatewayClient\Gateway;

class Im extends Service
{

    use ImFriendTrait;
    use ImGroupTrait;
    use ImMessageTrait;
    use ImNoticeTrait;
    use ImStatTrait;

    public function getCsUser()
    {
        $csUserIds = [];
        $onlineUserIds = [];

        $cache = new SettingCache();

        $imInfo = $cache->get('im');

        Gateway::$registerAddress = $this->getRegisterAddress();

        if (!empty($imInfo['cs_user1_id'])) {
            $csUserIds[] = $imInfo['cs_user1_id'];
            if (Gateway::isUidOnline($imInfo['cs_user1_id'])) {
                $onlineUserIds[] = $imInfo['cs_user1_id'];
            }
        }

        if (!empty($imInfo['cs_user2_id'])) {
            $csUserIds[] = $imInfo['cs_user2_id'];
            if (Gateway::isUidOnline($imInfo['cs_user2_id'])) {
                $onlineUserIds[] = $imInfo['cs_user2_id'];
            }
        }

        if (!empty($imInfo['cs_user3_id'])) {
            $csUserIds[] = $imInfo['cs_user3_id'];
            if (Gateway::isUidOnline($imInfo['cs_user3_id'])) {
                $onlineUserIds[] = $imInfo['cs_user3_id'];
            }
        }

        if (count($onlineUserIds) > 0) {
            $key = array_rand($onlineUserIds);
            $userId = $onlineUserIds[$key];
        } else {
            $key = array_rand($csUserIds);
            $userId = $csUserIds[$key];
        }

        return $this->getImUser($userId);
    }

    public function getInitInfo()
    {
        $loginUser = $this->getLoginUser();

        $user = $this->getImUser($loginUser->id);

        $mine = [
            'id' => $user->id,
            'username' => $user->name,
            'avatar' => $user->avatar,
            'sign' => $user->sign,
            'status' => $user->status,
        ];

        $friend = $this->handleFriendList($user);
        $group = $this->handleGroupList($user);

        return [
            'mine' => $mine,
            'friend' => $friend,
            'group' => $group,
        ];
    }

    public function getGroupUsers()
    {
        $id = $this->request->getQuery('id');

        $validator = new ImGroupValidator();

        $group = $validator->checkGroup($id);

        $groupRepo = new ImGroupRepo();

        $users = $groupRepo->findUsers($group->id);

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

    public function getFriendStatus()
    {
        $id = $this->request->getQuery('id');

        $validator = new ImUserValidator();

        $friend = $validator->checkUser($id);

        /**
         * 对方设置隐身，不返回真实情况
         */
        if ($friend->status == 'hide') {
            return 'unknown';
        }

        Gateway::$registerAddress = $this->getRegisterAddress();

        return Gateway::isUidOnline($friend->id) ? 'online' : 'offline';
    }

    public function bindUser()
    {
        $loginUser = $this->getLoginUser();

        $user = $this->getImUser($loginUser->id);

        $clientId = $this->request->getPost('client_id');

        Gateway::$registerAddress = $this->getRegisterAddress();

        Gateway::bindUid($clientId, $user->id);

        $userRepo = new ImUserRepo();

        $chatGroups = $userRepo->findGroups($user->id);

        if ($chatGroups->count() > 0) {
            foreach ($chatGroups as $group) {
                Gateway::joinGroup($clientId, $this->getGroupName($group->id));
            }
        }

        $this->pushOnlineTips($user);
    }

    public function updateStatus()
    {
        $loginUser = $this->getLoginUser();

        $user = $this->getImUser($loginUser->id);

        $status = $this->request->getPost('status');

        $validator = new ImUserValidator();

        $validator->checkSign($status);

        $user->update(['status' => $status]);

        $this->pushOnlineTips($user);
    }

    public function updateSignature()
    {
        $loginUser = $this->getLoginUser();

        $user = $this->getImUser($loginUser->id);

        $sign = $this->request->getPost('sign');

        $validator = new ImUserValidator();

        $sign = $validator->checkSign($sign);

        $user->update(['sign' => $sign]);

        return $user;
    }

    public function updateSkin()
    {
        $loginUser = $this->getLoginUser();

        $user = $this->getImUser($loginUser->id);

        $skin = $this->request->getPost('skin');

        $validator = new ImUserValidator();

        $skin = $validator->checkSkin($skin);

        $user->update(['skin' => $skin]);

        return $user;
    }

    protected function pushOnlineTips(ImUserModel $user)
    {
        /**
         * 隐身状态不推送消息
         */
        if ($user->status == 'hide') {
            return;
        }

        $onlinePushTime = $this->persistent->online_push_time;

        /**
         * 避免频繁推送消息
         */
        if ($onlinePushTime && time() - $onlinePushTime < 600) {
            return;
        }

        $this->persistent->online_push_time = time();

        $userRepo = new ImUserRepo();

        $friendUsers = $userRepo->findFriendUsers($user->id);

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
                    'status' => $user->status == 'online' ? 'online' : 'offline',
                ]);
                Gateway::sendToUid($friendUser->friend_id, $content);
            }
        }
    }

    protected function handleFriendList(ImUserModel $user)
    {
        $userRepo = new ImUserRepo();

        $friendGroups = $userRepo->findFriendGroups($user->id);
        $friendUsers = $userRepo->findFriendUsers($user->id);

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

        $ids = kg_array_column($friendUsers->toArray(), 'friend_id');

        $users = $userRepo->findByIds($ids);

        $mappings = [];

        /**
         * 用户可以设置状态为 ['online', 'hide']
         * 列表在线状态识别为 ['online', 'offline']
         */
        foreach ($users as $user) {
            $status = in_array($user->status, ['online', 'offline']) ? $user->status : 'offline';
            $mappings[$user->id] = [
                'id' => $user->id,
                'username' => $user->name,
                'avatar' => $user->avatar,
                'sign' => $user->sign,
                'status' => $status,
            ];
        }

        foreach ($items as $key => $item) {
            foreach ($friendUsers as $friendUser) {
                $friend = $mappings[$friendUser->friend_id];
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

    protected function handleGroupList(ImUserModel $user)
    {
        $userRepo = new ImUserRepo();

        $groups = $userRepo->findGroups($user->id);

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

    protected function getImUser($id)
    {
        $repo = new ImUserRepo();

        return $repo->findById($id);
    }

    protected function getGroupName($id)
    {
        return "group_{$id}";
    }

    protected function getRegisterAddress()
    {
        $config = $this->getDI()->get('config');

        return $config->websocket->register_address;
    }

}
