<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Chapter as ChapterModel;
use App\Models\ChapterLive as ChapterLiveModel;
use App\Models\Course as CourseModel;
use App\Models\CourseUser as CourseUserModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Teacher extends Repository
{

    public function paginateLiveChapters(array $courseIds, $page = 1, $limit = 15)
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
    public function findLiveCourses($userId)
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
