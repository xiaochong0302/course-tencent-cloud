<?php

namespace App\Http\Home\Controllers;

use App\Services\Logic\Search\Course as CourseSearchService;
use App\Services\Logic\Search\Group as GroupSearchService;
use App\Services\Logic\Search\User as UserSearchService;

/**
 * @RoutePrefix("/search")
 */
class SearchController extends Controller
{

    /**
     * @Get("/", name="home.search.index")
     */
    public function indexAction()
    {
        $query = $this->request->get('query', ['trim', 'string']);
        $type = $this->request->get('type', ['trim', 'string'], 'course');

        if (empty($query)) {
            $this->response->redirect(['for' => 'home.course.list']);
        }

        $this->seo->prependTitle(['搜索', $query]);

        $service = $this->getSearchService($type);

        $hotQueries = $service->getHotQuery();

        $relatedQueries = $service->getRelatedQuery($query);

        $pager = $service->search();

        $this->view->setVar('hot_queries', $hotQueries);
        $this->view->setVar('related_queries', $relatedQueries);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @param string $type
     * @return CourseSearchService|GroupSearchService|UserSearchService
     */
    protected function getSearchService($type)
    {
        switch ($type) {
            case 'group':
                $service = new GroupSearchService();
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
