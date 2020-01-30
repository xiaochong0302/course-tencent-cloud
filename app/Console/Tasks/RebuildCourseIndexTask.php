<?php

namespace App\Console\Tasks;

use App\Repos\Course as CourseRepo;
use App\Searchers\CourseDocumenter;
use App\Searchers\CourseSearcher;
use App\Services\CourseIndexSyncer;

class RebuildCourseIndexTask extends Task
{

    /**
     * @var \App\Library\Cache\Backend\Redis
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

        $this->rebuild();
    }

    protected function rebuild()
    {
        $key = $this->getCacheKey();

        $courseIds = $this->redis->sRandMember($key, 100);

        if (!$courseIds) return;

        $courseRepo = new CourseRepo();

        $courses = $courseRepo->findByIds($courseIds);

        if ($courses->count() == 0) {
            return;
        }

        $document = new CourseDocumenter();

        $searcher = new CourseSearcher();

        $index = $searcher->getXS()->getIndex();

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

        $this->redis->sRem($key, ...$courseIds);
    }

    protected function getCacheKey()
    {
        $syncer = new CourseIndexSyncer();

        return $syncer->getCacheKey();
    }

}
