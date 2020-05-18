<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\Search\CourseSearch;

/**
 * @RoutePrefix("/search")
 */
class SearchController extends Controller
{

    /**
     * @Get("/", name="web.search.show")
     */
    public function showAction()
    {
        $service = new CourseSearch();
        dd($service->handle());
        exit;
    }

}
