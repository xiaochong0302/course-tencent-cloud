<?php

namespace App\Services\Logic\Consult;

use App\Builders\ConsultList as ConsultListBuilder;
use App\Repos\Consult as ConsultRepo;
use App\Services\Logic\Service as LogicService;

class ConsultList extends LogicService
{

    public function paginate($params, $sort, $page, $limit)
    {
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
