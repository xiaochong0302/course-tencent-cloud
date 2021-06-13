<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Repos\Course as CourseRepo;
use App\Services\Sync\CourseScore as CourseScoreSync;
use App\Services\Utils\CourseScore as CourseScoreService;

class SyncCourseScoreTask extends Task
{

    public function mainAction()
    {
        $redis = $this->getRedis();

        $key = $this->getSyncKey();

        $courseIds = $redis->sRandMember($key, 1000);

        if (!$courseIds) return;

        $courseRepo = new CourseRepo();

        $courses = $courseRepo->findByIds($courseIds);

        if ($courses->count() == 0) return;

        $service = new CourseScoreService();

        foreach ($courses as $course) {
            $service->handle($course);
        }

        $redis->sRem($key, ...$courseIds);
    }

    protected function getSyncKey()
    {
        $sync = new CourseScoreSync();

        return $sync->getSyncKey();
    }

}
