<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Library\Utils\Lock as LockUtil;
use App\Repos\Course as CourseRepo;
use App\Services\Search\CourseDocument;
use App\Services\Search\CourseSearcher;
use App\Services\Sync\CourseIndex as CourseIndexSync;

class SyncCourseIndexTask extends Task
{

    public function mainAction(): void
    {
        $taskLockKey = $this->getTaskLockKey();

        $taskLockId = LockUtil::addLock($taskLockKey);

        if (!$taskLockId) return;

        $redis = $this->getRedis();

        $key = $this->getSyncKey();

        $courseIds = $redis->sRandMember($key, 1000);

        if (empty($courseIds)) return;

        $courseRepo = new CourseRepo();

        $courses = $courseRepo->findByIds($courseIds);

        if ($courses->count() == 0) return;

        echo '------ end sync course index ------' . PHP_EOL;

        $document = new CourseDocument();

        $handler = new CourseSearcher();

        $index = $handler->getXS()->getIndex();

        $index->openBuffer();

        foreach ($courses as $course) {

            $doc = $document->setDocument($course);

            if ($course->published == 1) {
                $index->update($doc);
            } else {
                $index->del($course->id);
            }
        }

        $index->closeBuffer();

        $redis->sRem($key, ...$courseIds);

        echo '------ end sync course index ------' . PHP_EOL;

        LockUtil::releaseLock($taskLockKey, $taskLockId);
    }

    protected function getSyncKey(): string
    {
        $sync = new CourseIndexSync();

        return $sync->getSyncKey();
    }

}
