<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Teacher\Console;

use App\Builders\ConsultList as ConsultListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\TeacherConsult as TeacherConsultRepo;
use App\Services\Logic\Service as LogicService;

class ConsultList extends LogicService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();
        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $params['user_id'] = $user->id;

        $params['status'] = $params['status'] ?? null;

        if ($params['status'] == 'pending') {
            $params['replied'] = 0;
        } elseif ($params['status'] == 'replied') {
            $params['replied'] = 1;
        }

        $repo = new TeacherConsultRepo();

        $pager = $repo->paginate($params, $sort, $page, $limit);

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
        $chapters = $builder->getChapters($consults);
        $users = $builder->getUsers($consults);

        $items = [];

        foreach ($consults as $consult) {

            $course = $courses[$consult['course_id']] ?? new \stdClass();
            $chapter = $chapters[$consult['chapter_id']] ?? new \stdClass();
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
                'chapter' => $chapter,
                'owner' => $owner,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
