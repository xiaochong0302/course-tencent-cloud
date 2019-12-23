<?php

namespace App\Console\Tasks;

use App\Models\Course as CourseModel;
use App\Searchers\Course as CourseSearcher;

class SyncIndexTask extends Task
{

    /**
     * @var \App\Library\Cache\Backend\Redis
     */
    protected $cache;

    public function mainAction()
    {
        $this->cache = $this->getDI()->get('cache');

        $this->handleCourseIndex();
    }

    protected function handleCourseIndex()
    {
        $keys = $this->cache->queryKeys('sync:index:course:');

        if (empty($keys)) {
            return;
        }

        $keys = array_slice($keys, 0, 100);

        $searcher = new CourseSearcher();

        $index = $searcher->getXS()->getIndex();

        $index->openBuffer();

        foreach ($keys as $key) {

            $lastKey = $this->cache->getRawKeyName($key);
            $content = $this->cache->get($lastKey);

            if (empty($content->id)) {
                continue;
            }

            $course = CourseModel::findFirstById($content->id);

            $document = $searcher->setDocument($course);

            if ($content->type == 'update') {
                $index->update($document);
            } elseif ($content->type == 'delete') {
                $index->del($course->id);
            } elseif ($content->type == 'restore') {
                $index->update($document);
            }

            $this->cache->delete($lastKey);
        }

        $index->closeBuffer();
    }

}
