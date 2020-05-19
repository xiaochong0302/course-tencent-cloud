<?php

namespace App\Services\Frontend\Search;

use App\Services\Frontend\Service as FrontendService;
use App\Services\Search\CourseSearcher as CourseSearcherService;

class CourseRelatedQuery extends FrontendService
{

    public function handle($query, $limit = 10)
    {
        $searcher = new CourseSearcherService();

        return $searcher->getRelatedQuery($query, $limit);
    }

}
