<?php

namespace App\Services\Logic\Chapter;

use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Consult as ConsultRepo;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\Course\ConsultListTrait;
use App\Services\Logic\Service as LogicService;

class ConsultList extends LogicService
{

    use ChapterTrait;
    use ConsultListTrait;

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

        $consultRepo = new ConsultRepo();

        $pager = $consultRepo->paginate($params, $sort, $page, $limit);

        return $this->handleConsults($pager);
    }

}
