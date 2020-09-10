<?php

namespace App\Services\Frontend\Search;

use App\Services\Frontend\Service as FrontendService;

abstract class Handler extends FrontendService
{

    abstract function search();

    abstract function getHotQuery($limit, $type);

    abstract function getRelatedQuery($query, $limit);

}