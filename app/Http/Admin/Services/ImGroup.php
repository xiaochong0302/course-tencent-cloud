<?php

namespace App\Http\Admin\Services;

use App\Builders\ImGroupList as ImGroupListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\ImGroup as ImGroupModel;
use App\Models\ImGroupUser as ImGroupUserModel;
use App\Models\User as UserModel;
use App\Repos\ImGroup as ImGroupRepo;
use App\Repos\ImGroupUser as ImGroupUserRepo;
use App\Validators\ImGroup as ImGroupValidator;

class ImGroup extends Service
{

    public function getGroups()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $groupRepo = new ImGroupRepo();

        $pager = $groupRepo->paginate($params, $sort, $page, $limit);

        return $this->handleGroups($pager);
    }

    public function getGroup($id)
    {
        return $this->findOrFail($id);
    }

    public function createGroup()
    {
        $post = $this->request->getPost();

        $validator = new ImGroupValidator();

        $data = [];

        $data['name'] = $validator->checkName($post['name']);
        $data['about'] = $validator->checkAbout($post['about']);
        $data['type'] = $validator->checkType($post['type']);

        $group = new ImGroupModel();

        $group->create($data);

        return $group;
    }

    public function updateGroup($id)
    {
        $group = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new ImGroupValidator();

        $data = [];

        if (isset($post['name'])) {
            $data['name'] = $validator->checkName($post['name']);
        }

        if (isset($post['about'])) {
            $data['about'] = $validator->checkAbout($post['about']);
        }

        if (isset($post['avatar'])) {
            $data['avatar'] = $validator->checkAvatar($post['avatar']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        if (isset($post['owner_id'])) {
            $owner = $validator->checkGroupOwner($post['owner_id']);
            $data['owner_id'] = $owner->id;
            $this->handleGroupOwner($group, $owner);
        }

        $group->update($data);

        return $group;
    }

    public function deleteGroup($id)
    {
        $group = $this->findOrFail($id);

        $group->deleted = 1;

        $group->update();

        return $group;
    }

    public function restoreGroup($id)
    {
        $group = $this->findOrFail($id);

        $group->deleted = 0;

        $group->update();

        return $group;
    }

    protected function handleGroupOwner(ImGroupModel $group, UserModel $user)
    {
        $repo = new ImGroupUserRepo();

        $groupUser = $repo->findGroupUser($group->id, $user->id);

        if ($groupUser) return;

        $groupUser = new ImGroupUserModel();
        $groupUser->group_id = $group->id;
        $groupUser->user_id = $user->id;
        $groupUser->create();

        $group->user_count += 1;
        $group->update();
    }

    protected function handleGroups($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new ImGroupListBuilder();

            $pipeA = $pager->items->toArray();
            $pipeB = $builder->handleUsers($pipeA);
            $pipeC = $builder->objects($pipeB);

            $pager->items = $pipeC;
        }

        return $pager;
    }

    protected function findOrFail($id)
    {
        $validator = new ImGroupValidator();

        return $validator->checkGroup($id);
    }

}
