<?php

namespace App\Http\Web\Services;

use App\Builders\ImGroupList as ImGroupListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\ImGroup as ImGroupRepo;

class ImSummary extends Service
{

    public function getActiveGroups()
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

    public function getActiveUsers()
    {
    }

    public function getOnlineUsers()
    {
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

}
