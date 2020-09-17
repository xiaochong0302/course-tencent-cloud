<?php

namespace App\Services\Logic\Course;

use App\Builders\ConsultList as ConsultListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Consult as ConsultRepo;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service;

class ConsultList extends Service
{

    use CourseTrait;

    public function handle($id)
    {
        $course = $this->checkCourse($id);

        $pagerQuery = new PagerQuery();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $params = [
            'course_id' => $course->id,
            'private' => 0,
            'published' => 1,
        ];

        $consultRepo = new ConsultRepo();

        $pager = $consultRepo->paginate($params, $sort, $page, $limit);

        return $this->handleConsults($pager);
    }

    protected function handleConsults($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $consults = $pager->items->toArray();

        $builder = new ConsultListBuilder();

        $users = $builder->getUsers($consults);

        $items = [];

        foreach ($consults as $consult) {

            $owner = $users[$consult['owner_id']] ?? new \stdClass();

            $items[] = [
                'id' => $consult['id'],
                'question' => $consult['question'],
                'answer' => $consult['answer'],
                'like_count' => $consult['like_count'],
                'reply_time' => $consult['reply_time'],
                'create_time' => $consult['create_time'],
                'update_time' => $consult['update_time'],
                'owner' => $owner,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
