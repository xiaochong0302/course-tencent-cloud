<?php

namespace App\Services\Logic\Question;

use App\Library\Paginator\Query as PagerQuery;
use App\Models\Comment as CommentModel;
use App\Repos\Comment as CommentRepo;
use App\Services\Logic\Comment\CommentListTrait;
use App\Services\Logic\QuestionTrait;
use App\Services\Logic\Service as LogicService;

class CommentList extends LogicService
{

    use QuestionTrait;
    use CommentListTrait;

    public function handle($id)
    {
        $question = $this->checkQuestion($id);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['item_id'] = $question->id;
        $params['item_type'] = CommentModel::ITEM_QUESTION;
        $params['published'] = CommentModel::PUBLISH_APPROVED;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $commentRepo = new CommentRepo();

        $pager = $commentRepo->paginate($params, $sort, $page, $limit);

        return $this->handleComments($pager);
    }

}
