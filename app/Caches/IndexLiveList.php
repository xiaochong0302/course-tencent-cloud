<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterLive as ChapterLiveModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\User as UserRepo;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class IndexLiveList extends Cache
{

    protected $lifetime = 3600;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'index_live_list';
    }

    public function getContent($id = null)
    {
        $limit = 8;

        $lives = $this->findChapterLives();

        if ($lives->count() == 0) return [];

        $chapterIds = kg_array_column($lives->toArray(), 'chapter_id');

        $chapterRepo = new ChapterRepo();

        $chapters = $chapterRepo->findByIds($chapterIds);

        $chapterMapping = [];

        foreach ($chapters as $chapter) {
            $chapterMapping[$chapter->id] = $chapter;
        }

        $courseIds = kg_array_column($lives->toArray(), 'course_id');

        $courseRepo = new CourseRepo();

        $courses = $courseRepo->findByIds($courseIds);

        $teacherIds = kg_array_column($courses->toArray(), 'teacher_id');

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($teacherIds);

        $courseMapping = [];

        foreach ($courses as $course) {
            $courseMapping[$course->id] = $course;
        }

        $userMapping = [];

        foreach ($users as $user) {
            $userMapping[$user->id] = $user;
        }

        $result = [];

        $flag = [];

        foreach ($lives as $live) {

            $chapter = $chapterMapping[$live->chapter_id];
            $course = $courseMapping[$chapter->course_id];
            $teacher = $userMapping[$course->teacher_id];

            $teacherInfo = [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'title' => $teacher->title,
                'avatar' => $teacher->avatar,
            ];

            $chapterInfo = [
                'id' => $chapter->id,
                'title' => $chapter->title,
            ];

            $courseInfo = [
                'id' => $course->id,
                'title' => $course->title,
                'cover' => $course->cover,
                'teacher' => $teacherInfo,
            ];

            if (!isset($flag[$course->id]) && count($flag) < $limit) {
                $flag[$course->id] = 1;
                $result[] = [
                    'id' => $live->id,
                    'status' => $live->status,
                    'start_time' => $live->start_time,
                    'end_time' => $live->end_time,
                    'course' => $courseInfo,
                    'chapter' => $chapterInfo,
                ];
            }
        }

        return $result;
    }

    /**
     * @return ResultsetInterface|Resultset|ChapterLiveModel[]
     */
    protected function findChapterLives()
    {
        $startTime = strtotime('today');
        $endTime = strtotime('+30 days');

        return $this->modelsManager->createBuilder()
            ->columns('cl.*')
            ->addFrom(ChapterLiveModel::class, 'cl')
            ->join(ChapterModel::class, 'cl.chapter_id = c.id', 'c')
            ->betweenWhere('start_time', $startTime, $endTime)
            ->andWhere('published = 1')
            ->andWhere('deleted = 0')
            ->orderBy('start_time ASC')
            ->getQuery()
            ->execute();
    }

}
