<?php

namespace App\Services\Logic\Search;

use App\Services\Logic\Service;

abstract class Handler extends Service
{

    abstract function search();

    abstract function getHotQuery($limit, $type);

    abstract function getRelatedQuery($query, $limit);

}