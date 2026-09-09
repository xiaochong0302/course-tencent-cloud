<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Search;

use XS;

class CourseSearcher extends Searcher
{

    public function __construct()
    {
        $this->xs = $this->getXS();
    }

    public function getXS(): XS
    {
        $filename = config_path('xs.course.ini');

        return new XS($filename);
    }

    public function getHighlightFields(): array
    {
        return ['title', 'summary'];
    }

}
