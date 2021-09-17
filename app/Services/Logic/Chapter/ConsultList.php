<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Chapter;

use App\Library\Paginator\Query as PagerQuery;
use App\Models\Consult as ConsultModel;
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
            'published' => ConsultModel::PUBLISH_APPROVED,
            'deleted' => 0,
            'private' => 0,
        ];

        $consultRepo = new ConsultRepo();

        $pager = $consultRepo->paginate($params, $sort, $page, $limit);

        return $this->handleConsults($pager);
    }

}
