<?php

namespace App\Http\Desktop\Services;

use App\Models\ImUser as ImUserModel;
use App\Repos\ImGroup as ImGroupRepo;
use App\Repos\ImUser as ImUserRepo;
use App\Validators\ImGroup as ImGroupValidator;
use App\Validators\ImUser as ImUserValidator;
use GatewayClient\Gateway;

class Im extends Service
{

    use ImCsTrait;
    use ImFriendTrait;
    use ImGroupTrait;
    use ImMessageTrait;
    use ImNoticeTrait;
    use ImStatTrait;

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
        $id = $this->request->getQuery('id', 'int');

        $validator = new ImGroupValidator();

        $group = $validator->checkGroup($id);

        Gateway::$registerAddress = $this->getRegisterAddress();

        $userIds = Gateway::getUidListByGroup($this->getGroupName($group->id));

        if (count($userIds) == 0) {
            return [];
        }

        $userRepo = new ImUserRepo();

        $users = $userRepo->findByIds($userIds);

        $baseUrl = kg_cos_url();

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
        $id = $this->request->getQuery('id', 'int');

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

        $clientId = $this->request->getPost('client_id', 'string');

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

        $status = $this->request->getPost('status', 'string');

        $validator = new ImUserValidator();

        $validator->checkSign($status);

        $user->update(['status' => $status]);

        $this->pushOnlineTips($user);
    }

    public function updateSignature()
    {
        $loginUser = $this->getLoginUser();

        $user = $this->getImUser($loginUser->id);

        $sign = $this->request->getPost('sign', 'string');

        $validator = new ImUserValidator();

        $sign = $validator->checkSign($sign);

        $user->update(['sign' => $sign]);

        return $user;
    }

    public function updateSkin()
    {
        $loginUser = $this->getLoginUser();

        $user = $this->getImUser($loginUser->id);

        $skin = $this->request->getPost('skin', 'string');

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

        $baseUrl = kg_cos_url();

        $mapping = [];

        /**
         * 用户可以设置状态为 ['online', 'hide']
         * 列表在线状态识别为 ['online', 'offline']
         */
        foreach ($users->toArray() as $user) {
            $status = in_array($user['status'], ['online', 'offline']) ? $user['status'] : 'offline';
            $user['avatar'] = $baseUrl . $user['avatar'];
            $mapping[$user['id']] = [
                'id' => $user['id'],
                'username' => $user['name'],
                'avatar' => $user['avatar'],
                'sign' => $user['sign'],
                'status' => $status,
            ];
        }

        foreach ($items as $key => $item) {
            foreach ($friendUsers as $friendUser) {
                $friend = $mapping[$friendUser->friend_id];
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

        $groupUsers = $userRepo->findGroupUsers($user->id);

        if ($groupUsers->count() == 0) {
            return [];
        }

        $groupRepo = new ImGroupRepo();

        $ids = kg_array_column($groupUsers->toArray(), 'group_id');

        $groups = $groupRepo->findByIds($ids);

        $baseUrl = kg_cos_url();

        $mapping = [];

        foreach ($groups->toArray() as $group) {
            $mapping[$group['id']] = [
                'id' => $group['id'],
                'groupname' => $group['name'],
                'avatar' => $baseUrl . $group['avatar'],
            ];
        }

        $result = [];

        foreach ($groupUsers as $groupUser) {
            $result[] = $mapping[$groupUser->group_id];
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
        $config = $this->getConfig();

        return $config->path('websocket.register_address');
    }

}
