<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Chapter;

use App\Library\Paginator\Query as PagerQuery;
use App\Models\Comment as CommentModel;
use App\Repos\Comment as CommentRepo;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\Comment\ListTrait;
use App\Services\Logic\Service as LogicService;

class CommentList extends LogicService
{

    use ChapterTrait;
    use ListTrait;

    public function handle($id)
    {
        $chapter = $this->checkChapter($id);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['item_id'] = $chapter->id;
        $params['item_type'] = CommentModel::ITEM_CHAPTER;
        $params['published'] = CommentModel::PUBLISH_APPROVED;
        $params['parent_id'] = 0;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $commentRepo = new CommentRepo();

        $pager = $commentRepo->paginate($params, $sort, $page, $limit);

        return $this->handleComments($pager);
    }

}
