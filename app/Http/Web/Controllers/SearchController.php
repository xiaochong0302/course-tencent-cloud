<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\Search\CourseHotQuery as CourseHotQueryService;
use App\Services\Frontend\Search\CourseList as CourseListService;
use App\Services\Frontend\Search\CourseRelatedQuery as CourseRelatedQueryService;
use App\Traits\Response as ResponseTrait;

/**
 * @RoutePrefix("/search")
 */
class SearchController extends Controller
{

    use ResponseTrait;

    /**
     * @Get("/", name="web.search.list")
     */
    public function listAction()
    {
        $query = $this->request->get('query', ['trim']);

        $service = new CourseHotQueryService();

        $hotQueries = $service->handle();

        $service = new CourseRelatedQueryService();

        $relatedQueries = $service->handle($query);

        $service = new CourseListService();

        $pager = $service->handle();

        $pager->items = kg_array_object($pager->items);

        $this->view->setVar('hot_queries', $hotQueries);
        $this->view->setVar('related_queries', $relatedQueries);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/queries/hot", name="web.search.hot_queries")
     */
    public function hotQueriesAction()
    {
        $service = new CourseHotQueryService();

        $queries = $service->handle();

        return $this->jsonSuccess(['queries' => $queries]);
    }

    /**
     * @Get("/queries/related", name="web.search.related_queries")
     */
    public function relatedQueriesAction()
    {
        $query = $this->request->get('query', ['trim']);

        $service = new CourseRelatedQueryService();

        $queries = $service->handle($query);

        return $this->jsonSuccess(['queries' => $queries]);
    }

}
