<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Tag;

use App\Builders\TagFollowList as TagFollowListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\TagFollow as TagFollowRepo;
use App\Services\Logic\Service as LogicService;

class FollowList extends LogicService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['user_id'] = $user->id;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $followRepo = new TagFollowRepo();

        $pager = $followRepo->paginate($params, $sort, $page, $limit);

        return $this->handleTags($pager);
    }

    public function handleTags($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $builder = new TagFollowListBuilder();

        $relations = $pager->items->toArray();

        $tags = $builder->getTags($relations);

        $items = [];

        foreach ($relations as $relation) {
            $tag = $tags[$relation['tag_id']] ?? new \stdClass();
            $items[] = $tag;
        }

        $pager->items = $items;

        return $pager;
    }

}
