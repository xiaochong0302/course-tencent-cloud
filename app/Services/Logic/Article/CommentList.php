<?php

namespace App\Services\Logic\Article;

use App\Library\Paginator\Query as PagerQuery;
use App\Models\Comment as CommentModel;
use App\Repos\Comment as CommentRepo;
use App\Services\Logic\ArticleTrait;
use App\Services\Logic\Comment\CommentList as CommentListService;

class CommentList extends CommentListService
{

    use ArticleTrait;

    public function handle($id)
    {
        $article = $this->checkArticle($id);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['item_type'] = CommentModel::ITEM_ARTICLE;
        $params['item_id'] = $article->id;
        $params['published'] = 1;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $commentRepo = new CommentRepo();

        $pager = $commentRepo->paginate($params, $sort, $page, $limit);

        return $this->handlePager($pager);
    }

}
