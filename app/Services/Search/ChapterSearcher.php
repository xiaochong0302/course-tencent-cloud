<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Services\Search;

use XS;

class ChapterSearcher extends Searcher
{

    public function __construct()
    {
        $this->xs = $this->getXS();
    }

    public function getXS(): XS
    {
        $filename = config_path('xs.chapter.ini');

        return new XS($filename);
    }

    public function getHighlightFields(): array
    {
        return ['title', 'summary'];
    }

}
