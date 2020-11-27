<?php

namespace App\Services\Logic\Chapter;

use App\Library\Paginator\Query as PagerQuery;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\Consult\ConsultList as ConsultListHandler;
use App\Services\Logic\Service;

class ConsultList extends Service
{

    use ChapterTrait;

    public function handle($id)
    {
        $chapter = $this->checkChapter($id);

        $pagerQuery = new PagerQuery();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $params = [
            'chapter_id' => $chapter->id,
            'private' => 0,
            'published' => 1,
        ];

        $service = new ConsultListHandler();

        return $service->paginate($params, $sort, $page, $limit);
    }

}
