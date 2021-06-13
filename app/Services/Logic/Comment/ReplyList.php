<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Comment;

use App\Library\Paginator\Query as PagerQuery;
use App\Models\Comment as CommentModel;
use App\Repos\Comment as CommentRepo;
use App\Services\Logic\CommentTrait;
use App\Services\Logic\Service as LogicService;

class ReplyList extends LogicService
{

    use CommentTrait;
    use ListTrait;

    public function handle($id)
    {
        $comment = $this->checkComment($id);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['parent_id'] = $comment->id;
        $params['published'] = CommentModel::PUBLISH_APPROVED;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $commentRepo = new CommentRepo();

        $pager = $commentRepo->paginate($params, $sort, $page, $limit);

        return $this->handleComments($pager);
    }

}
