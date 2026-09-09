<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Page;

use App\Models\Page as PageModel;
use App\Services\Logic\ContentTrait;
use App\Services\Logic\PageTrait;
use App\Services\Logic\Service as LogicService;

class PageInfo extends LogicService
{

    use PageTrait;
    use ContentTrait;

    public function handle(string|int $id): array
    {
        $page = $this->checkPage($id);

        $this->incrPageViewCount($page);

        $this->eventsManager->fire('Page:afterView', $this, $page);

        return $this->handlePage($page);
    }

    protected function handlePage(PageModel $page): array
    {
        $content = kg_parse_markdown($page->content);
        $content = $this->handleContent($content);

        return [
            'id' => $page->id,
            'alias' => $page->alias,
            'title' => $page->title,
            'content' => $content,
            'keywords' => $page->keywords,
            'summary' => $page->summary,
            'published' => $page->published,
            'deleted' => $page->deleted,
            'view_count' => $page->view_count,
            'create_time' => $page->create_time,
            'update_time' => $page->update_time,
        ];
    }

    protected function incrPageViewCount(PageModel $page): void
    {
        $page->view_count += 1;

        $page->update();
    }

}
