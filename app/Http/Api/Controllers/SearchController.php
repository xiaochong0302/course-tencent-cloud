<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api\Controllers;

use App\Services\Logic\Search\Chapter as ChapterSearchService;
use App\Services\Logic\Search\Course as CourseSearchService;

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

        if (empty($query)) {
            return $this->jsonSuccess([
                'pager' => [
                    'total_items' => 0,
                    'total_pages' => 0,
                    'items' => [],
                ]
            ]);
        }

        $service = $this->getSearchService($type);

        $pager = $service->search();

        return $this->jsonPaginate($pager);
    }

    protected function getSearchService(string $type = 'course')
    {
        return match ($type) {
            'chapter' => new ChapterSearchService(),
            default => new CourseSearchService(),
        };
    }

}
