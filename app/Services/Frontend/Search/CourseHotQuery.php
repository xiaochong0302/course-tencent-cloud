<?php

namespace App\Services\Frontend\Search;

use App\Services\Frontend\Service as FrontendService;
use App\Services\Search\CourseSearcher as CourseSearcherService;

class CourseHotQuery extends FrontendService
{

    public function handle($limit = 10, $type = 'total')
    {
        $searcher = new CourseSearcherService();

        return $searcher->getHotQuery($limit, $type);
    }

}
