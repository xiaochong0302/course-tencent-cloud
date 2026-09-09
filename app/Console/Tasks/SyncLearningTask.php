<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Library\Utils\Lock as LockUtil;
use App\Models\Course as CourseModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\Learning as LearningModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\ChapterUser as ChapterUserRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\Learning as LearningRepo;
use App\Services\Sync\Learning as LearningSyncService;

class SyncLearningTask extends Task
{

    public function mainAction(): void
    {
        $taskLockKey = $this->getTaskLockKey();

        $taskLockId = LockUtil::addLock($taskLockKey);

        if (!$taskLockId) return;

        $redis = $this->getRedis();

        $sync = new LearningSyncService();

        $syncKey = $sync->getSyncKey();

        $requestIds = $redis->sRandMember($syncKey, 300);

        echo sprintf('pending requests: %s', count($requestIds)) . PHP_EOL;

        if (empty($requestIds)) return;

        echo '------ start sync learning task ------' . PHP_EOL;

        foreach ($requestIds as $requestId) {
            try {
                $itemKey = $sync->getItemKey($requestId);
                $this->handleLearning($itemKey);
            } catch (\Exception $e) {
                $logger = $this->getLogger('sync');
                $logger->error('Sync Learning Exception: ' . kg_json_encode([
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'message' => $e->getMessage(),
                        'request_id' => $requestId,
                    ]));
            } finally {
                $redis->sRem($syncKey, $requestId);
            }
        }

        echo '------ end sync learning task ------' . PHP_EOL;

        LockUtil::releaseLock($taskLockKey, $taskLockId);
    }

    protected function handleLearning(string $itemKey): void
    {
        $cache = $this->getCache();

        /**
         * @var LearningModel|null $cacheLearning
         */
        $cacheLearning = $cache->get($itemKey);

        if (!$cacheLearning) return;

        $learningRepo = new LearningRepo();

        $dbLearning = $learningRepo->findByRequestId($cacheLearning->request_id);

        if (!$dbLearning) {

            $cacheLearning->create();

            $this->updateChapterUser($cacheLearning);

        } else {

            $dbLearning->duration = $cacheLearning->duration;
            $dbLearning->position = $cacheLearning->position;
            $dbLearning->active_time = $cacheLearning->active_time;

            $dbLearning->update();

            $this->updateChapterUser($dbLearning);
        }

        $cache->delete($itemKey);
    }

    protected function updateChapterUser(LearningModel $learning): void
    {
        $chapterUserRepo = new ChapterUserRepo();

        $chapterUser = $chapterUserRepo->findPlanChapterUser($learning->chapter_id, $learning->user_id, $learning->plan_id);

        if (!$chapterUser) return;

        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($learning->chapter_id);

        if (!$chapter) return;

        $chapterUser->duration += $learning->duration;
        $chapterUser->active_time = $learning->active_time;

        /**
         * 消费规则
         *
         * 1.点播观看时间大于时长30%
         * 2.直播观看时间超过10分钟
         * 3.图文浏览即消费
         * 4.文档浏览即消费
         */
        if ($chapter->model == CourseModel::MODEL_VOD) {

            $duration = $chapter->attrs['duration'] ?: 300;

            $progress = floor(100 * $chapterUser->duration / $duration);

            /**
             * 过于接近结束位置当作已结束处理，播放位置为起点０
             */
            $playPosition = $duration - $learning->position > 10 ? floor($learning->position) : 0;

            $chapterUser->position = $playPosition;
            $chapterUser->progress = min($progress, 100);
            $chapterUser->consumed = $chapterUser->duration > 0.3 * $duration ? 1 : 0;

        } elseif ($chapter->model == CourseModel::MODEL_LIVE) {

            $chapterUser->consumed = $chapterUser->duration > 600 ? 1 : 0;

        } elseif ($chapter->model == CourseModel::MODEL_READ) {

            $chapterUser->consumed = 1;

        } elseif ($chapter->model == CourseModel::MODEL_DOC) {

            $chapterUser->consumed = 1;
        }

        $chapterUser->update();

        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->findPlanCourseUser($learning->course_id, $learning->user_id, $learning->plan_id);

        $this->updateCourseUserDuration($courseUser, $learning);

        if ($chapterUser->consumed == 1) {
            $this->updateCourseUserProgress($courseUser);
        }
    }

    protected function updateCourseUserDuration(CourseUserModel $courseUser, LearningModel $learning): void
    {
        $courseRepo = new CourseRepo();

        $userLearnings = $courseRepo->findUserLearnings($courseUser->course_id, $courseUser->user_id, $courseUser->plan_id);

        if ($userLearnings->count() == 0) return;

        $duration = 0;

        foreach ($userLearnings as $userLearning) {
            $duration += $userLearning->duration;
        }

        $courseUser->duration = $duration;
        $courseUser->active_time = $learning->active_time;
        $courseUser->update();
    }

    protected function updateCourseUserProgress(CourseUserModel $courseUser): void
    {
        $courseRepo = new CourseRepo();

        $courseLessons = $courseRepo->findLessons($courseUser->course_id);

        if ($courseLessons->count() == 0) return;

        $userLearnings = $courseRepo->findUserLearnings($courseUser->course_id, $courseUser->user_id, $courseUser->plan_id);

        if ($userLearnings->count() == 0) return;

        $consumedUserLearnings = [];

        foreach ($userLearnings->toArray() as $userLearning) {
            if ($userLearning['consumed'] == 1) {
                $consumedUserLearnings[] = $userLearning;
            }
        }

        if (count($consumedUserLearnings) == 0) return;

        $courseLessonIds = kg_array_column($courseLessons->toArray(), 'id');
        $consumedUserLessonIds = kg_array_column($consumedUserLearnings, 'chapter_id');
        $consumedLessonIds = array_intersect($courseLessonIds, $consumedUserLessonIds);

        $totalCount = count($courseLessonIds);
        $consumedCount = count($consumedLessonIds);
        $progress = intval(100 * $consumedCount / $totalCount);

        $courseUser->progress = $progress;
        $courseUser->update();
    }

}
