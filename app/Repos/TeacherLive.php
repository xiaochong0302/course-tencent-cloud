<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Chapter as ChapterModel;
use App\Models\ChapterLive as ChapterLiveModel;
use App\Models\Course as CourseModel;
use App\Models\CourseUser as CourseUserModel;

class TeacherLive extends Repository
{

    public function paginate($userId, $page = 1, $limit = 15)
    {
        $columns = [
            'course_id' => 'course.id',
            'course_title' => 'course.title',
            'chapter_id' => 'chapter.id',
            'chapter_title' => 'chapter.title',
            'live_start_time' => 'cl.start_time',
            'live_end_time' => 'cl.end_time',
        ];

        $builder = $this->modelsManager->createBuilder()
            ->columns($columns)
            ->addFrom(ChapterModel::class, 'chapter')
            ->join(ChapterLiveModel::class, 'chapter.id = cl.chapter_id', 'cl')
            ->join(CourseModel::class, 'chapter.course_id = course.id', 'course')
            ->join(CourseUserModel::class, 'course.id = cu.course_id', 'cu')
            ->where('cu.user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('cu.role_type = :role_type:', ['role_type' => CourseUserModel::ROLE_TEACHER])
            ->andWhere('course.model = :model:', ['model' => CourseModel::MODEL_LIVE])
            ->andWhere('cl.start_time > :start_time:', ['start_time' => strtotime('today')])
            ->orderBy('cl.start_time ASC');

        $pager = new PagerQueryBuilder([
            'builder' => $builder,
            'page' => $page,
            'limit' => $limit,
        ]);

        return $pager->paginate();
    }

}
