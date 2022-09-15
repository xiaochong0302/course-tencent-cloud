<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Page;

use App\Models\Page as PageModel;
use App\Services\Logic\PageTrait;
use App\Services\Logic\Service as LogicService;

class PageInfo extends LogicService
{

    use PageTrait;

    public function handle($id)
    {
        $page = $this->checkPage($id);

        return $this->handlePage($page);
    }

    protected function handlePage(PageModel $page)
    {
        return [
            'id' => $page->id,
            'title' => $page->title,
            'keywords' => $page->keywords,
            'content' => $page->content,
            'published' => $page->published,
            'deleted' => $page->deleted,
            'create_time' => $page->create_time,
            'update_time' => $page->update_time,
        ];
    }

}
