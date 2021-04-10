<?php

namespace App\Services\Logic\User;

use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Article as ArticleRepo;
use App\Services\Logic\Article\ArticleList as ArticleListService;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\UserTrait;

class ArticleList extends LogicService
{

    use UserTrait;

    public function handle($id)
    {
        $user = $this->checkUser($id);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['owner_id'] = $user->id;
        $params['published'] = 1;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $articleRepo = new ArticleRepo();

        $pager = $articleRepo->paginate($params, $sort, $page, $limit);

        return $this->handleArticles($pager);
    }

    protected function handleArticles($pager)
    {
        $service = new ArticleListService();

        return $service->handleArticles($pager);
    }

}
