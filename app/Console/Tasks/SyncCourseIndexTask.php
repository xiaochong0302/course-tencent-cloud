<?php

namespace App\Console\Tasks;

use App\Repos\Course as CourseRepo;
use App\Services\Search\CourseDocument;
use App\Services\Search\CourseSearcher;
use App\Services\Syncer\CourseIndex as CourseIndexSyncer;

class SyncCourseIndexTask extends Task
{

    public function mainAction()
    {
        $cache = $this->getCache();

        $redis = $cache->getRedis();

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
        $syncer = new CourseIndexSyncer();

        return $syncer->getSyncKey();
    }

}
