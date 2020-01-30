<?php

namespace App\Console\Tasks;

use App\Models\Course as CourseModel;
use App\Models\Learning as LearningModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\ChapterUser as ChapterUserRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\Learning as LearningRepo;
use Phalcon\Cli\Task;

class LearningTask extends Task
{

    /**
     * @var \App\Library\Cache\Backend\Redis
     */
    protected $cache;

    public function mainAction()
    {
        $this->cache = $this->getDI()->get('cache');

        $keys = $this->cache->queryKeys('learning:');

        if (!$keys) return;

        $keys = array_slice($keys, 0, 500);

        foreach ($keys as $key) {
            $lastKey = $this->cache->getRawKeyName($key);
            $this->handleLearning($lastKey);
        }
    }

    protected function handleLearning($key)
    {
        $content = $this->cache->get($key);

        if (!$content) return;

        $learningRepo = new LearningRepo();

        $learning = $learningRepo->findByRequestId($content['request_id']);

        if (!$learning) {
            $learning = new LearningModel();
            $learning->create($content);
        } else {
            $learning->duration += $content['duration'];
            $learning->update();
        }

        $this->updateChapterUser($content['chapter_id'], $content['user_id'], $content['duration'], $content['position']);

        $this->cache->delete($key);
    }

    protected function updateChapterUser($chapterId, $userId, $duration = 0, $position = 0)
    {
        $chapterUserRepo = new ChapterUserRepo();

        $chapterUser = $chapterUserRepo->findChapterUser($chapterId, $userId);

        if (!$chapterUser) return;

        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($chapterId);

        if (!$chapter) return;

        $chapterModel = $chapter->attrs['model'];

        $chapterUser->duration += $duration;

        /**
         * 消费规则
         * 1.点播观看时间大于时长30%
         * 2.直播观看时间超过10分钟
         * 3.图文浏览即消费
         */
        if ($chapterModel == CourseModel::MODEL_VOD) {

            $chapterDuration = $chapter->attrs['duration'] ?: 300;

            $progress = floor(100 * $chapterUser->duration / $chapterDuration);

            $chapterUser->position = floor($position);
            $chapterUser->progress = $progress < 100 ? $progress : 100;
            $chapterUser->consumed = $chapterUser->duration > 0.3 * $chapterDuration ? 1 : 0;

        } elseif ($chapterModel == CourseModel::MODEL_LIVE) {

            $chapterUser->consumed = $chapterUser->duration > 600 ? 1 : 0;

        } elseif ($chapterModel == CourseModel::MODEL_READ) {

            $chapterUser->consumed = 1;
        }

        $chapterUser->update();

        if ($chapterUser->consumed == 1) {
            $this->updateCourseUser($chapterUser->course_id, $chapterUser->user_id);
        }
    }

    protected function updateCourseUser($courseId, $userId)
    {
        $courseRepo = new CourseRepo();

        $courseLessons = $courseRepo->findLessons($courseId);

        if ($courseLessons->count() == 0) {
            return;
        }

        $userLearnings = $courseRepo->findConsumedUserLearnings($courseId, $userId);

        if ($userLearnings->count() == 0) {
            return;
        }

        $duration = 0;

        foreach ($userLearnings as $learning) {
            $duration += $learning->duration;
        }

        $courseLessonIds = kg_array_column($courseLessons->toArray(), 'id');
        $userLessonIds = kg_array_column($userLearnings->toArray(), 'chapter_id');
        $consumedLessonIds = array_intersect($courseLessonIds, $userLessonIds);

        $totalCount = count($courseLessonIds);
        $consumedCount = count($consumedLessonIds);
        $progress = intval(100 * $consumedCount / $totalCount);

        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->findCourseUser($courseId, $userId);

        $courseUser->progress = $progress;
        $courseUser->duration = $duration;
        $courseUser->update();
    }

}
