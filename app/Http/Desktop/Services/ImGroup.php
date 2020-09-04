<?php

namespace App\Http\Desktop\Services;

use App\Builders\ImGroupList as ImGroupListBuilder;
use App\Builders\ImGroupUserList as ImGroupUserListBuilder;
use App\Caches\ImGroupActiveUserList as ImGroupActiveUserListCache;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\ImGroup as ImGroupModel;
use App\Models\ImUser as ImUserModel;
use App\Repos\ImGroup as ImGroupRepo;
use App\Repos\ImGroupUser as ImGroupUserRepo;
use App\Repos\User as UserRepo;
use App\Validators\ImGroup as ImGroupValidator;
use App\Validators\ImGroupUser as ImGroupUserValidator;

class ImGroup extends Service
{

    public function getGroups()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['published'] = 1;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $groupRepo = new ImGroupRepo();

        $pager = $groupRepo->paginate($params, $sort, $page, $limit);

        return $this->handleGroups($pager);
    }

    public function getGroup($id)
    {
        $validator = new ImGroupValidator();

        $group = $validator->checkGroup($id);

        $userRepo = new UserRepo();

        $owner = $userRepo->findById($group->owner_id);

        return [
            'id' => $group->id,
            'type' => $group->type,
            'name' => $group->name,
            'avatar' => $group->avatar,
            'about' => $group->about,
            'user_count' => $group->user_count,
            'msg_count' => $group->msg_count,
            'owner' => [
                'id' => $owner->id,
                'name' => $owner->name,
                'avatar' => $owner->avatar,
                'title' => $owner->title,
                'about' => $owner->about,
                'vip' => $owner->vip,
            ],
        ];
    }

    public function getGroupUsers($id)
    {
        $validator = new ImGroupValidator();

        $group = $validator->checkGroup($id);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['group_id'] = $group->id;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $repo = new ImGroupUserRepo();

        $pager = $repo->paginate($params, $sort, $page, $limit);

        return $this->handleGroupUsers($pager);
    }

    public function getActiveGroupUsers($id)
    {
        $cache = new ImGroupActiveUserListCache();

        $result = $cache->get($id);

        return $result ?: [];
    }

    public function updateGroup($id)
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $validator = new ImGroupValidator();

        $group = $validator->checkGroup($id);

        $validator->checkOwner($user->id, $group->owner_id);

        $data = [];

        /**
         * 课程群组不允许改名
         */
        if (!empty($post['name']) && $group->type == ImGroupModel::TYPE_CHAT) {
            $data['name'] = $validator->checkName($post['name']);
        }

        if (!empty($post['about'])) {
            $data['about'] = $validator->checkAbout($post['about']);
        }

        if (!empty($post['avatar'])) {
            $data['avatar'] = $validator->checkAvatar($post['avatar']);
        }

        $group->update($data);

        return $group;
    }

    public function deleteGroupUser($groupId, $userId)
    {
        $loginUser = $this->getLoginUser();

        $validator = new ImGroupUserValidator();

        $group = $validator->checkGroup($groupId);

        $user = $validator->checkUser($userId);

        $validator->checkOwner($loginUser->id, $group->owner_id);

        $groupUser = $validator->checkGroupUser($groupId, $userId);

        $groupUser->delete();

        $this->decrGroupUserCount($group);

        $this->decrUserGroupCount($user);
    }

    protected function handleGroupUsers($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $builder = new ImGroupUserListBuilder();

        $relations = $pager->items->toArray();

        $users = $builder->getUsers($relations);

        $items = [];

        foreach ($relations as $relation) {
            $user = $users[$relation['user_id']] ?? new \stdClass();
            $items[] = $user;
        }

        $pager->items = $items;

        return $pager;
    }

    protected function handleGroups($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $builder = new ImGroupListBuilder();

        $groups = $pager->items->toArray();

        $users = $builder->getUsers($groups);

        $baseUrl = kg_ci_base_url();

        $items = [];

        foreach ($groups as $group) {

            $group['avatar'] = $baseUrl . $group['avatar'];
            $group['owner'] = $users[$group['owner_id']] ?? new \stdClass();

            $items[] = [
                'id' => $group['id'],
                'type' => $group['type'],
                'name' => $group['name'],
                'avatar' => $group['avatar'],
                'about' => $group['about'],
                'user_count' => $group['user_count'],
                'msg_count' => $group['msg_count'],
                'owner' => $group['owner'],
            ];
        }

        $pager->items = $items;

        return $pager;
    }

    protected function decrGroupUserCount(ImGroupModel $group)
    {
        if ($group->user_count > 0) {
            $group->user_count -= 1;
            $group->update();
        }
    }

    protected function decrUserGroupCount(ImUserModel $user)
    {
        if ($user->group_count > 0) {
            $user->group_count -= 1;
            $user->update();
        }
    }

}
