<?php

namespace App\Services\Frontend\Search;

use App\Library\Paginator\Adapter\XunSearch as XunSearchPaginator;
use App\Library\Paginator\Query as PagerQuery;
use App\Services\Frontend\Service as FrontendService;
use App\Services\Search\CourseSearcher as CourseSearcherService;

class CourseSearch extends FrontendService
{

    public function handle()
    {

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $courseSearcher = new CourseSearcherService();

        $paginator = new XunSearchPaginator([
            'xs' => $courseSearcher->getXS(),
            'highlight' => $courseSearcher->getHighlightFields(),
            'query' => $params['query'],
            'page' => $page,
            'limit' => $limit,
        ]);

        $pager = $paginator->getPaginate();

        dd($pager);
    }

}
