<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\User;

use App\Library\Paginator\Query as PagerQuery;
use App\Models\Question as QuestionModel;
use App\Repos\Question as QuestionRepo;
use App\Services\Logic\Question\QuestionList as QuestionListService;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\UserTrait;

class QuestionList extends LogicService
{

    use UserTrait;

    public function handle($id)
    {
        $user = $this->checkUserCache($id);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['owner_id'] = $user->id;
        $params['published'] = QuestionModel::PUBLISH_APPROVED;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $articleRepo = new QuestionRepo();

        $pager = $articleRepo->paginate($params, $sort, $page, $limit);

        return $this->handleQuestions($pager);
    }

    protected function handleQuestions($pager)
    {
        $service = new QuestionListService();

        return $service->handleQuestions($pager);
    }

}
