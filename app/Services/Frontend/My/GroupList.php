<?php

namespace App\Services\Frontend\My;

use App\Builders\ImGroupList as ImGroupListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\ImGroup as ImGroupRepo;
use App\Services\Frontend\Service as FrontendService;
use App\Services\Frontend\User\GroupList as UserGroupListService;

class GroupList extends FrontendService
{

    public function handle($scope)
    {
        $result = [];

        if ($scope == 'joined') {
            $result = $this->handleJoinedGroups();
        } elseif ($scope == 'owned') {
            $result = $this->handleOwnedGroups();
        }

        return $result;
    }

    public function handleJoinedGroups()
    {
        $user = $this->getLoginUser();

        $service = new UserGroupListService();

        return $service->handle($user->id);
    }

    public function handleOwnedGroups()
    {
        $user = $this->getLoginUser();

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['owner_id'] = $user->id;
        $params['published'] = 1;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $repo = new ImGroupRepo();

        $pager = $repo->paginate($params, $sort, $page, $limit);

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
