<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Services\Logic\Search\Article as ArticleSearch;
use App\Services\Logic\Search\Course as CourseSearch;
use App\Services\Logic\Search\Question as QuestionSearch;

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
            return $this->jsonPaginate($pager);
        }

        $service = $this->getSearchService($type);

        $pager = $service->search();

        return $this->jsonPaginate($pager);
    }

    /**
     * @param string $type
     * @return ArticleSearch|QuestionSearch|CourseSearch
     */
    protected function getSearchService($type)
    {
        switch ($type) {
            case 'article':
                $service = new ArticleSearch();
                break;
            case 'question':
                $service = new QuestionSearch();
                break;
            default:
                $service = new CourseSearch();
                break;
        }

        return $service;
    }

}
