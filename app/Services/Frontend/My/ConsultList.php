<?php

namespace App\Services\Frontend\My;

use App\Builders\ConsultList as ConsultListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Consult as ConsultRepo;
use App\Services\Frontend\Service as FrontendService;
use App\Services\Frontend\UserTrait;

class ConsultList extends FrontendService
{

    use UserTrait;

    public function handle()
    {
        $user = $this->getLoginUser();

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['user_id'] = $user->id;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $consultRepo = new ConsultRepo();

        $pager = $consultRepo->paginate($params, $sort, $page, $limit);

        return $this->handleConsults($pager);
    }

    protected function handleConsults($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $builder = new ConsultListBuilder();

        $consults = $pager->items->toArray();

        $courses = $builder->getCourses($consults);

        $items = [];

        foreach ($consults as $consult) {

            $course = $courses[$consult['course_id']] ?? [];

            $items[] = [
                'id' => $consult['id'],
                'question' => $consult['question'],
                'answer' => $consult['answer'],
                'like_count' => $consult['like_count'],
                'create_time' => $consult['create_time'],
                'update_time' => $consult['update_time'],
                'course' => $course,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
