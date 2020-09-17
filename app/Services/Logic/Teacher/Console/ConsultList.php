<?php

namespace App\Services\Logic\Teacher\Console;

use App\Builders\ConsultList as ConsultListBuilder;
use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Consult as ConsultModel;
use App\Models\Course as CourseModel;
use App\Models\CourseUser as CourseUserModel;
use App\Services\Logic\Service;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class ConsultList extends Service
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $courses = $this->findTeachingCourses($user->id);

        if ($courses->count() == 0) {
            return [];
        }

        $params['status'] = $params['status'] ?? null;

        if ($params['status'] == 'pending') {
            $params['replied'] = 0;
        } elseif ($params['status'] == 'replied') {
            $params['replied'] = 1;
        }

        $params['course_id'] = kg_array_column($courses->toArray(), 'id');

        $pager = $this->paginate($params, $page, $limit);

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
                'update_time' => $consult['update_time'],
                'course' => $course,
                'chapter' => $chapter,
                'owner' => $owner,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

    protected function paginate($where, $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(ConsultModel::class);

        $builder->where('published = 1');

        if (!empty($where['course_id'])) {
            $builder->inWhere('course_id', $where['course_id']);
        }

        if (isset($where['replied'])) {
            if ($where['replied'] == 1) {
                $builder->andWhere('reply_time > 0');
            } else {
                $builder->andWhere('reply_time = 0');
            }
        }

        $builder->orderBy('priority ASC,id DESC');

        $pager = new PagerQueryBuilder([
            'builder' => $builder,
            'page' => $page,
            'limit' => $limit,
        ]);

        return $pager->paginate();
    }

    /**
     * @param int $userId
     * @return ResultsetInterface|Resultset|CourseModel[]
     */
    protected function findTeachingCourses($userId)
    {
        return $this->modelsManager->createBuilder()
            ->columns('c.*')
            ->addFrom(CourseModel::class, 'c')
            ->join(CourseUserModel::class, 'c.id = cu.course_id', 'cu')
            ->where('cu.user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('cu.role_type = :role_type:', ['role_type' => CourseUserModel::ROLE_TEACHER])
            ->getQuery()->execute();
    }

}
