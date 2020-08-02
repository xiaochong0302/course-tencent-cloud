<?php

namespace App\Services\Frontend\Teaching;

use App\Builders\ConsultList as ConsultListBuilder;
use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Consult as ConsultModel;
use App\Models\Course as CourseModel;
use App\Models\CourseUser as CourseUserModel;
use App\Services\Frontend\Service as FrontendService;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class ConsultList extends FrontendService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $pagerQuery = new PagerQuery();

        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $courses = $this->findUserTeachingCourses($user->id);

        if ($courses->count() == 0) {
            return [];
        }

        $courseIds = kg_array_column($courses->toArray(), 'id');

        $pager = $this->paginate($courseIds, $page, $limit);

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
                'rating' => $consult['rating'],
                'like_count' => $consult['like_count'],
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

    protected function paginate($courseIds, $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder()
            ->from(ConsultModel::class)
            ->inWhere('course_id', $courseIds)
            ->andWhere('published = 1')
            ->orderBy('priority ASC');

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
    protected function findUserTeachingCourses($userId)
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
