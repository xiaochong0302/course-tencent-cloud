<?php

namespace App\Services\Frontend\Teacher;

use App\Library\Paginator\Query as PagerQuery;
use App\Models\User;
use App\Repos\User as UserRepo;
use App\Services\Frontend\Service as FrontendService;

class TeacherList extends FrontendService
{

    public function handle()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['edu_role'] = User::EDU_ROLE_TEACHER;
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

        $items = [];

        $baseUrl = kg_ci_base_url();

        foreach ($users as $user) {

            $user['avatar'] = $baseUrl . $user['avatar'];

            $items[] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'avatar' => $user['avatar'],
                'title' => $user['title'],
                'about' => $user['about'],
                'location' => $user['location'],
                'create_time' => $user['create_time'],
                'last_login_time' => $user['last_login_time'],
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
