<?php

namespace App\Services\Logic\Tag;

use App\Library\Paginator\Query as PagerQuery;
use App\Models\User as UserModel;
use App\Repos\Tag as TagRepo;
use App\Repos\TagFollow as TagFollowRepo;
use App\Services\Logic\Service as LogicService;

class TagList extends LogicService
{

    public function handle()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['published'] = 1;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $tagRepo = new TagRepo();

        $pager = $tagRepo->paginate($params, $sort, $page, $limit);

        return $this->handleTags($pager);
    }

    public function handleTags($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $user = $this->getLoginUser();

        $followTagIds = $this->getFollowTagIds($user);

        $tags = $pager->items->toArray();

        $baseUrl = kg_cos_url();

        $items = [];

        foreach ($tags as $tag) {

            $tag['icon'] = $baseUrl . $tag['icon'];
            $tag['me']['followed'] = in_array($tag['id'], $followTagIds) ? 1 : 0;

            $items[] = [
                'id' => $tag['id'],
                'name' => $tag['name'],
                'icon' => $tag['icon'],
                'follow_count' => $tag['follow_count'],
                'me' => $tag['me'],
            ];
        }

        $pager->items = $items;

        return $pager;
    }

    protected function getFollowTagIds(UserModel $user)
    {
        if ($user->id == 0) return [];

        $followRepo = new TagFollowRepo();

        $where = ['user_id' => $user->id];

        $pager = $followRepo->paginate($where, 'latest', 1, 1000);

        $result = [];

        if ($pager->total_items > 0) {
            foreach ($pager->items as $item) {
                $result[] = $item->tag_id;
            }
        }

        return $result;
    }

}
