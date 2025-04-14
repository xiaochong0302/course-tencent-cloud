<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Library\Paginator\Adapter;

use App\Library\Paginator\Query as PaginatorQuery;
use Phalcon\Paginator\Adapter\NativeArray as PhNativeArray;
use stdClass;

class NativeArray extends PhNativeArray
{

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var array
     */
    protected $params = [];

    public function paginate(): stdClass
    {
        $pager = parent::paginate();

        $query = new PaginatorQuery();

        $this->baseUrl = $query->getBaseUrl();
        $this->params = $query->getParams();

        $pager->first = $this->buildPageUrl($pager->first);
        $pager->previous = $this->buildPageUrl($pager->previous);
        $pager->next = $this->buildPageUrl($pager->next);
        $pager->last = $this->buildPageUrl($pager->last);

        return $pager;
    }

    protected function buildPageUrl($page)
    {
        $this->params['page'] = $page;

        return $this->baseUrl . '?' . http_build_query($this->params);
    }

}
