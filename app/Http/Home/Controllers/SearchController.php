<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Services\Logic\Search\Chapter as ChapterSearchService;
use App\Services\Logic\Search\Course as CourseSearchService;

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

    protected function getSearchService(string $type = 'course')
    {
        return match ($type) {
            'chapter' => new ChapterSearchService(),
            default => new CourseSearchService(),
        };
    }

}
