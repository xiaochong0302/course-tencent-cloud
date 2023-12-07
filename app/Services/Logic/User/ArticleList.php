<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\User;

use App\Library\Paginator\Query as PagerQuery;
use App\Models\Article as ArticleModel;
use App\Repos\Article as ArticleRepo;
use App\Services\Logic\Article\ArticleList as ArticleListService;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\UserTrait;

class ArticleList extends LogicService
{

    use UserTrait;

    public function handle($id)
    {
        $user = $this->checkUserCache($id);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['owner_id'] = $user->id;
        $params['published'] = ArticleModel::PUBLISH_APPROVED;
        $params['deleted'] = 0;

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
