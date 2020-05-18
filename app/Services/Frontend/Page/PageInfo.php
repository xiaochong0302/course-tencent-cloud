<?php

namespace App\Services\Frontend\Page;

use App\Models\Page as PageModel;
use App\Services\Frontend\PageTrait;
use App\Services\Frontend\Service as FrontendService;

class PageInfo extends FrontendService
{

    use PageTrait;

    public function handle($id)
    {
        $page = $this->checkPageCache($id);

        return $this->handlePage($page);
    }

    protected function handlePage(PageModel $page)
    {
        return [
            'id' => $page->id,
            'title' => $page->title,
            'content' => $page->content,
            'create_time' => $page->create_time,
            'update_time' => $page->update_time,
        ];
    }

}
