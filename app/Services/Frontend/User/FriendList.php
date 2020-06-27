<?php

namespace App\Services\Frontend\User;

use App\Builders\FriendUserList as FriendUserListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\ImFriendUser as ImFriendUserRepo;
use App\Services\Frontend\Service as FrontendService;
use App\Services\Frontend\UserTrait;

class FriendList extends FrontendService
{

    use UserTrait;

    public function handle($id)
    {
        $user = $this->checkUserCache($id);

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

        $builder = new FriendUserListBuilder();

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
