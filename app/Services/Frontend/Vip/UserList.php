<?php

namespace App\Services\Frontend\Vip;

use App\Library\Paginator\Query as PagerQuery;
use App\Repos\User as UserRepo;
use App\Services\Frontend\Service as FrontendService;

class UserList extends FrontendService
{

    public function handle()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['vip'] = 1;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $userRepo = new UserRepo();

        $pager = $userRepo->paginate($params, $sort, $page, $limit);

        return $this->handleUsers($pager);
    }

    protected function handleUsers($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $users = $pager->items->toArray();

        $baseUrl = kg_ci_base_url();

        $items = [];

        foreach ($users as $user) {

            $user['avatar'] = $baseUrl . $user['avatar'];

            $items[] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'avatar' => $user['avatar'],
                'title' => $user['title'],
                'about' => $user['about'],
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
