<?php

namespace App\Services\Logic\Page;

use App\Models\Page as PageModel;
use App\Services\Logic\PageTrait;
use App\Services\Logic\Service;

class PageInfo extends Service
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
            'content' => $page->content,
            'create_time' => $page->create_time,
            'update_time' => $page->update_time,
        ];
    }

}
