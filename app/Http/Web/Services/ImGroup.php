<?php

namespace App\Http\Web\Services;

use App\Builders\ImGroupUserList as ImGroupUserListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\ImGroupUser as ImGroupUserRepo;
use App\Validators\ImGroup as ImGroupValidator;
use App\Validators\ImGroupUser as ImGroupUserValidator;

class ImGroup extends Service
{

    public function getGroups()
    {

    }

    public function updateGroup($id)
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $validator = new ImGroupValidator();

        $group = $validator->checkGroup($id);

        $validator->checkOwner($user->id, $group->owner_id);

        $data = [];

        if (!empty($post['name'])) {
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

    public function getGroup($id)
    {
        $validator = new ImGroupValidator();

        return $validator->checkGroup($id);
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

    public function deleteGroupUser($groupId, $userId)
    {
        $loginUser = $this->getLoginUser();

        $validator = new ImGroupUserValidator();

        $group = $validator->checkGroup($groupId);

        $validator->checkOwner($loginUser->id, $group->owner_id);

        $groupUser = $validator->checkGroupUser($groupId, $userId);

        $groupUser->delete();
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

}
