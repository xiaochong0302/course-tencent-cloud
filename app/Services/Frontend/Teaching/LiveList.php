<?php

namespace App\Services\Frontend\Teaching;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Chapter as ChapterModel;
use App\Models\ChapterLive as ChapterLiveModel;
use App\Models\Course as CourseModel;
use App\Models\CourseUser as CourseUserModel;
use App\Services\Frontend\Service as FrontendService;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class LiveList extends FrontendService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $courses = $this->findUserTeachingCourses($user->id);

        if ($courses->count() == 0) {
            return [];
        }

        $courseMapping = [];

        foreach ($courses as $course) {
            $courseMapping[$course->id] = [
                'id' => $course->id,
                'title' => $course->title,
            ];
        }

        $courseIds = kg_array_column($courses->toArray(), 'id');

        $pagerQuery = new PagerQuery();

        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $pager = $this->paginate($courseIds, $page, $limit);

        if ($pager->total_items == 0) {
            return $pager;
        }

        $items = [];

        foreach ($pager->items as $item) {
            $items[] = [
                'course' => $courseMapping[$item->course_id],
                'chapter' => [
                    'id' => $item->id,
                    'title' => $item->title,
                ],
                'start_time' => $item->start_time,
                'end_time' => $item->end_time,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

    protected function paginate($courseIds, $page = 1, $limit = 15)
    {
        $startTime = strtotime('today');

        $builder = $this->modelsManager->createBuilder()
            ->columns(['c.id', 'c.title', 'c.course_id', 'cl.start_time', 'cl.end_time'])
            ->addFrom(ChapterModel::class, 'c')
            ->join(ChapterLiveModel::class, 'c.id = cl.chapter_id', 'cl')
            ->inWhere('cl.course_id', $courseIds)
            ->andWhere('cl.start_time > :start_time:', ['start_time' => $startTime])
            ->orderBy('cl.start_time ASC');

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
            ->andWhere('c.model = :model:', ['model' => CourseModel::MODEL_LIVE])
            ->getQuery()->execute();
    }

}
