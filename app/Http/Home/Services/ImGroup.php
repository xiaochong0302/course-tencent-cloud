<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Services;

use App\Builders\ImGroupList as ImGroupListBuilder;
use App\Builders\ImGroupUserList as ImGroupUserListBuilder;
use App\Caches\ImGroupActiveUserList as ImGroupActiveUserListCache;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\ImGroup as ImGroupModel;
use App\Repos\ImGroup as ImGroupRepo;
use App\Repos\ImGroupUser as ImGroupUserRepo;
use App\Repos\User as UserRepo;
use App\Validators\ImGroup as ImGroupValidator;

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

        $owner = $this->handleGroupOwner($group);

        return [
            'id' => $group->id,
            'type' => $group->type,
            'name' => $group->name,
            'avatar' => $group->avatar,
            'about' => $group->about,
            'user_count' => $group->user_count,
            'msg_count' => $group->msg_count,
            'owner' => $owner,
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

    protected function handleGroupOwner(ImGroupModel $group)
    {
        if ($group->owner_id == 0) return new \stdClass();

        $userRepo = new UserRepo();

        $owner = $userRepo->findById($group->owner_id);

        return [
            'id' => $owner->id,
            'name' => $owner->name,
            'avatar' => $owner->avatar,
            'title' => $owner->title,
            'about' => $owner->about,
            'vip' => $owner->vip,
        ];
    }

    protected function handleGroupUsers($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $builder = new ImGroupUserListBuilder();

        $stepA = $pager->items->toArray();
        $stepB = $builder->handleUsers($stepA);

        $pager->items = $stepB;

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

        $baseUrl = kg_cos_url();

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

}
