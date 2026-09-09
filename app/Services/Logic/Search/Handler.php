<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Search;

use App\Services\Logic\Service as LogicService;
use Phalcon\Paginator\RepositoryInterface as PagerRepoInterface;

abstract class Handler extends LogicService
{

    abstract function search(): PagerRepoInterface;

    abstract function getHotQuery(int $limit, string $type): array;

    abstract function getRelatedQuery(string $query, int $limit): array;

    protected function handleKeywords(string $str): string
    {
        return kg_substr($str, 0, 50, '');
    }

}
