<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Im;

use App\Builders\ImGroupUserList as ImGroupUserListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\ImGroupUser as ImGroupUserRepo;
use App\Services\Logic\ImGroupTrait;
use App\Services\Logic\Service as LogicService;

class GroupUserList extends LogicService
{

    use ImGroupTrait;

    public function handle($id)
    {
        $group = $this->checkImGroup($id);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['group_id'] = $group->id;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $repo = new ImGroupUserRepo();

        $pager = $repo->paginate($params, $sort, $page, $limit);

        return $this->handleGroupUsers($pager);
    }

    protected function handleGroupUsers($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $builder = new ImGroupUserListBuilder();

        $relations = $pager->items->toArray();

        $users = $builder->getUsers($relations);

        $items = [];

        foreach ($relations as $relation) {
            $user = $users[$relation['user_id']] ?? new \stdClass();
            $items[] = $user;
        }

        $pager->items = $items;

        return $pager;
    }

}
