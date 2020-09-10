<?php

namespace App\Console\Tasks;

use App\Models\Course as CourseModel;
use App\Models\Learning as LearningModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\ChapterUser as ChapterUserRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\Learning as LearningRepo;
use App\Services\Syncer\Learning as LearningSyncer;

class SyncLearningTask extends Task
{

    public function mainAction()
    {
        $cache = $this->getCache();

        $redis = $cache->getRedis();

        $syncer = new LearningSyncer();

        $syncKey = $syncer->getSyncKey();

        $requestIds = $redis->sMembers($syncKey);

        if (!$requestIds) return;

        foreach ($requestIds as $requestId) {

            $itemKey = $syncer->getItemKey($requestId);

            $this->handleLearning($itemKey);

            $redis->sRem($syncKey, $requestId);
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

            $this->updateChapterUser($cacheLearning);

        } else {

            $dbLearning->duration += $cacheLearning->duration;
            $dbLearning->position = $cacheLearning->position;
            $dbLearning->active_time = $cacheLearning->active_time;

            $dbLearning->update();

            $this->updateChapterUser($dbLearning);
        }

        $this->cache->delete($itemKey);
    }

    /**
     * @param LearningModel $learning
     */
    protected function updateChapterUser(LearningModel $learning)
    {
        $chapterUserRepo = new ChapterUserRepo();

        $chapterUser = $chapterUserRepo->findPlanChapterUser($learning->chapter_id, $learning->user_id, $learning->plan_id);

        if (!$chapterUser) return;

        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($learning->chapter_id);

        if (!$chapter) return;

        $chapterUser->duration += $learning->duration;

        /**
         * 消费规则
         *
         * 1.点播观看时间大于时长30%
         * 2.直播观看时间超过10分钟
         * 3.图文浏览即消费
         */
        if ($chapter->model == CourseModel::MODEL_VOD) {

            $duration = $chapter->attrs['duration'] ?: 300;

            $progress = floor(100 * $chapterUser->duration / $duration);

            $chapterUser->position = floor($learning->position);
            $chapterUser->progress = $progress < 100 ? $progress : 100;
            $chapterUser->consumed = $chapterUser->duration > 0.3 * $duration ? 1 : 0;

        } elseif ($chapter->model == CourseModel::MODEL_LIVE) {

            $chapterUser->consumed = $chapterUser->duration > 600 ? 1 : 0;

        } elseif ($chapter->model == CourseModel::MODEL_READ) {

            $chapterUser->consumed = 1;
        }

        $chapterUser->update();

        if ($chapterUser->consumed == 1) {
            $this->updateCourseUser($learning);
        }
    }

    /**
     * @param LearningModel $learning
     */
    protected function updateCourseUser(LearningModel $learning)
    {
        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->findPlanCourseUser($learning->course_id, $learning->user_id, $learning->plan_id);

        if (!$courseUser) return;

        $courseRepo = new CourseRepo();

        $courseLessons = $courseRepo->findLessons($learning->course_id);

        if ($courseLessons->count() == 0) return;

        $userLearnings = $courseRepo->findUserLearnings($learning->course_id, $learning->user_id, $learning->plan_id);

        if ($userLearnings->count() == 0) return;

        $consumedUserLearnings = [];

        foreach ($userLearnings->toArray() as $userLearning) {
            if ($userLearning['consumed'] == 1) {
                $consumedUserLearnings[] = $userLearning;
            }
        }

        if (count($consumedUserLearnings) == 0) return;

        $duration = 0;

        foreach ($consumedUserLearnings as $userLearning) {
            $duration += $userLearning['duration'];
        }

        $courseLessonIds = kg_array_column($courseLessons->toArray(), 'id');
        $consumedUserLessonIds = kg_array_column($consumedUserLearnings, 'chapter_id');
        $consumedLessonIds = array_intersect($courseLessonIds, $consumedUserLessonIds);

        $totalCount = count($courseLessonIds);
        $consumedCount = count($consumedLessonIds);
        $progress = intval(100 * $consumedCount / $totalCount);

        $courseUser->progress = $progress;
        $courseUser->duration = $duration;
        $courseUser->update();
    }

}
