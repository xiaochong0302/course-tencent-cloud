<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Services\Logic\Search\Article as ArticleSearchService;
use App\Services\Logic\Search\Course as CourseSearchService;
use App\Services\Logic\Search\Question as QuestionSearchService;

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
            return $this->response->redirect(['for' => 'home.course.list']);
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
     * @return ArticleSearchService|QuestionSearchService|CourseSearchService
     */
    protected function getSearchService($type)
    {
        switch ($type) {
            case 'article':
                $service = new ArticleSearchService();
                break;
            case 'question':
                $service = new QuestionSearchService();
                break;
            default:
                $service = new CourseSearchService();
                break;
        }

        return $service;
    }

}
