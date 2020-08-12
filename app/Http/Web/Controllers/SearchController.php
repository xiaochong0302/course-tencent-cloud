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
     * @Get("/", name="web.search.index")
     */
    public function indexAction()
    {
        $query = $this->request->get('query', ['trim']);

        if (empty($query)) {
            return $this->response->redirect(['for' => 'web.course.list']);
        }

        $this->seo->prependTitle(['搜索', $query]);

        $service = new CourseHotQueryService();

        $hotQueries = $service->handle();

        $service = new CourseRelatedQueryService();

        $relatedQueries = $service->handle($query);

        $service = new CourseListService();

        $pager = $service->handle();

        $this->view->setVar('hot_queries', $hotQueries);
        $this->view->setVar('related_queries', $relatedQueries);
        $this->view->setVar('pager', $pager);
    }

}
