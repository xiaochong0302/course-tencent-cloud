<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Repos\Course as CourseRepo;
use App\Services\Search\CourseDocument;
use App\Services\Search\CourseSearcher;
use App\Services\Sync\CourseIndex as CourseIndexSync;

class SyncCourseIndexTask extends Task
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
    }

    protected function getSyncKey()
    {
        $sync = new CourseIndexSync();

        return $sync->getSyncKey();
    }

}
