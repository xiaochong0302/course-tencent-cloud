<?php

namespace App\Services\Logic\User;

use App\Builders\ImGroupUserList as ImGroupUserListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\ImGroupUser as ImGroupUserRepo;
use App\Services\Logic\UserTrait;

class GroupList extends LogicService
{

    use UserTrait;

    public function handle($id)
    {
        $user = $this->checkUser($id);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['user_id'] = $user->id;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $repo = new ImGroupUserRepo();

        $pager = $repo->paginate($params, $sort, $page, $limit);

        return $this->handleGroups($pager);
    }

    protected function handleGroups($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $builder = new ImGroupUserListBuilder();

        $relations = $pager->items->toArray();

        $groups = $builder->getGroups($relations);

        $items = [];

        foreach ($relations as $relation) {
            $group = $groups[$relation['group_id']] ?? new \stdClass();
            $items[] = $group;
        }

        $pager->items = $items;

        return $pager;
    }

}
