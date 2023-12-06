<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Consult;

use App\Builders\ConsultList as ConsultListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Consult as ConsultRepo;
use App\Services\Logic\Service as LogicService;

class ConsultList extends LogicService
{

    public function handle()
    {
        $pagerQuery = new PagerQuery();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();
        $params = $pagerQuery->getParams();

        $params['deleted'] = 0;

        $consultRepo = new ConsultRepo();

        $pager = $consultRepo->paginate($params, $sort, $page, $limit);

        return $this->handleConsults($pager);
    }

    public function handleConsults($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $builder = new ConsultListBuilder();

        $consults = $pager->items->toArray();
        $courses = $builder->getCourses($consults);
        $users = $builder->getUsers($consults);

        $items = [];

        foreach ($consults as $consult) {

            $course = $courses[$consult['course_id']] ?? new \stdClass();
            $owner = $users[$consult['owner_id']] ?? new \stdClass();

            $items[] = [
                'id' => $consult['id'],
                'question' => $consult['question'],
                'answer' => $consult['answer'],
                'priority' => $consult['priority'],
                'like_count' => $consult['like_count'],
                'reply_time' => $consult['reply_time'],
                'create_time' => $consult['create_time'],
                'course' => $course,
                'owner' => $owner,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
