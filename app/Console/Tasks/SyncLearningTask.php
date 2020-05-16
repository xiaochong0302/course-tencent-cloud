<?php

namespace App\Console\Tasks;

use App\Library\Cache\Backend\Redis as RedisCache;
use App\Models\Course as CourseModel;
use App\Models\Learning as LearningModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\ChapterUser as ChapterUserRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\Learning as LearningRepo;
use App\Services\Syncer\Learning as LearningSyncer;
use Phalcon\Cli\Task;

class SyncLearningTask extends Task
{

    /**
     * @var RedisCache
     */
    protected $cache;

    /**
     * @var \Redis
     */
    protected $redis;

    public function mainAction()
    {
        $this->cache = $this->getDI()->get('cache');

        $this->redis = $this->cache->getRedis();

        $syncer = new LearningSyncer();

        $syncKey = $syncer->getSyncKey();

        $requestIds = $this->redis->sMembers($syncKey);

        if (!$requestIds) return;

        foreach ($requestIds as $requestId) {

            $itemKey = $syncer->getItemKey($requestId);

            $this->handleLearning($itemKey);

            $this->redis->sRem($syncKey, $requestId);
        }
    }

    /**
     * @param string $itemKey
     */
    protected function handleLearning($itemKey)
    {
        /**
         * @var LearningModel $cacheLearning
         */
        $cacheLearning = $this->cache->get($itemKey);

        if (!$cacheLearning) return;

        $learningRepo = new LearningRepo();

        $dbLearning = $learningRepo->findByRequestId($cacheLearning->request_id);

        if (!$dbLearning) {
            $cacheLearning->create();
        } else {
            $dbLearning->duration = $cacheLearning->duration;
            $dbLearning->position = $cacheLearning->position;
            $dbLearning->update();
        }

        $this->updateChapterUser($dbLearning);

        $this->cache->delete($itemKey);
    }

    /**
     * @param LearningModel $learning
     */
    protected function updateChapterUser(LearningModel $learning)
    {
        $chapterUserRepo = new ChapterUserRepo();

        $chapterUser = $chapterUserRepo->findChapterUser($learning->chapter_id, $learning->user_id);

        if (!$chapterUser) return;

        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($learning->chapter_id);

        if (!$chapter) return;

        $chapterModel = $chapter->attrs['model'];

        $chapterUser->duration += $learning->duration;

        /**
         * 消费规则
         *
         * 1.点播观看时间大于时长30%
         * 2.直播观看时间超过10分钟
         * 3.图文浏览即消费
         */
        if ($chapterModel == CourseModel::MODEL_VOD) {

            $duration = $chapter->attrs['duration'] ?: 300;

            $progress = floor(100 * $chapterUser->duration / $duration);

            $chapterUser->position = floor($learning->position);
            $chapterUser->progress = $progress < 100 ? $progress : 100;
            $chapterUser->consumed = $chapterUser->duration > 0.3 * $duration ? 1 : 0;

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

    /**
     * @param int $courseId
     * @param int $userId
     */
    protected function updateCourseUser($courseId, $userId)
    {
        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->findCourseUser($courseId, $userId);

        if (!$courseUser) return;

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

        $courseUser->progress = $progress;
        $courseUser->duration = $duration;
        $courseUser->update();
    }

}
