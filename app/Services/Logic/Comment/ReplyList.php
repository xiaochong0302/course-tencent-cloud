<?php

namespace App\Services\Logic\Comment;

use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Comment as CommentRepo;
use App\Services\Logic\CommentTrait;
use App\Services\Logic\Service as LogicService;

class ReplyList extends LogicService
{

    use CommentTrait;
    use CommentListTrait;

    public function handle($id)
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['parent_id'] = $id;
        $params['published'] = 1;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $commentRepo = new CommentRepo();

        $pager = $commentRepo->paginate($params, $sort, $page, $limit);

        return $this->handlePager($pager);
    }

}
