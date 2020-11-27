<?php

namespace App\Http\Api\Controllers;

use App\Services\Logic\Search\Course as CourseSearchService;
use App\Services\Logic\Search\Group as GroupSearchService;
use App\Services\Logic\Search\User as UserSearchService;

/**
 * @RoutePrefix("/api/search")
 */
class SearchController extends Controller
{

    /**
     * @Get("/", name="api.search.index")
     */
    public function indexAction()
    {
        $query = $this->request->get('query', ['trim', 'string']);
        $type = $this->request->get('type', ['trim', 'string'], 'course');

        $pager = [
            'total_pages' => 0,
            'total_items' => 0,
            'items' => [],
        ];

        if (empty($query)) {
            return $this->jsonSuccess(['pager' => $pager]);
        }

        $service = $this->getSearchService($type);

        $pager = $service->search();

        return $this->jsonSuccess(['pager' => $pager]);
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
