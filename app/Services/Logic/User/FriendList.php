<?php

namespace App\Services\Logic\User;

use App\Builders\ImFriendUserList as ImFriendUserListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\ImFriendUser as ImFriendUserRepo;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\UserTrait;

class FriendList extends LogicService
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

        $repo = new ImFriendUserRepo();

        $pager = $repo->paginate($params, $sort, $page, $limit);

        return $this->handleFriends($pager);
    }

    protected function handleFriends($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $builder = new ImFriendUserListBuilder();

        $relations = $pager->items->toArray();

        $friends = $builder->getFriends($relations);

        $items = [];

        foreach ($relations as $relation) {
            $friend = $friends[$relation['friend_id']] ?? new \stdClass();
            $items[] = $friend;
        }

        $pager->items = $items;

        return $pager;
    }

}
