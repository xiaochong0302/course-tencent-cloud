<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\Search\Course as CourseSearchService;
use App\Services\Frontend\Search\Group as GroupSearchService;
use App\Services\Frontend\Search\User as UserSearchService;
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
        $query = $this->request->get('query', ['trim', 'string']);
        $type = $this->request->get('type', ['trim'], 'course');

        if (empty($query)) {
            return $this->response->redirect(['for' => 'web.course.list']);
        }

        $this->seo->prependTitle(['搜索', $query]);

        $service = $this->getSearchService($type);

        $hotQueries = $service->hotQuery();

        $relatedQueries = $service->relatedQuery($query);

        $pager = $service->search();

        $this->view->setVar('hot_queries', $hotQueries);
        $this->view->setVar('related_queries', $relatedQueries);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/form", name="web.search.form")
     */
    public function formAction()
    {

    }

    /**
     * @param string $type
     * @return CourseSearchService|GroupSearchService|UserSearchService
     */
    protected function getSearchService($type)
    {
        switch ($type) {
            case 'group':
                $service = new GroupSearchService;
                break;
            case 'user':
                $service = new UserSearchService();
                break;
            default:
                $service = new CourseSearchService();
                break;
        }

        return $service;
    }

}
