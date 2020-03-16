<?php

namespace App\Listeners;

use App\Caches\MaxCourseId as MaxCourseIdCache;
use App\Models\Course as CourseModel;
use App\Services\CourseCacheSyncer;
use App\Services\CourseIndexSyncer;
use Phalcon\Events\Event;

class CourseAdmin extends Listener
{

    protected $logger;

    public function __construct()
    {
        $this->logger = $this->getLogger();
    }

    public function afterCreate(Event $event, $source, CourseModel $course)
    {
        if ($course->published == 1) {
            $this->syncMaxIdCache();
            $this->syncCache($course);
            $this->syncIndex($course);
        }

        $this->logger->debug('Event: {event}, Source: {source}, Data: {data}', [
            'event' => $event->getType(),
            'source' => get_class($source),
            'data' => kg_json_encode($course),
        ]);
    }

    public function afterUpdate(Event $event, $source, CourseModel $course)
    {
        if ($course->published == 1) {
            $this->syncCache($course);
            $this->syncIndex($course);
        }

        $this->logger->debug('Event: {event}, Source: {source}, Data: {data}', [
            'event' => $event->getType(),
            'source' => get_class($source),
            'data' => kg_json_encode($course),
        ]);
    }

    public function afterDelete(Event $event, $source, CourseModel $course)
    {
        if ($course->published == 1) {
            $this->syncCache($course);
            $this->syncIndex($course);
        }

        $this->logger->debug('Event: {event}, Source: {source}, Data: {data}', [
            'event' => $event->getType(),
            'source' => get_class($source),
            'data' => kg_json_encode($course),
        ]);
    }

    public function afterRestore(Event $event, $source, CourseModel $course)
    {
        if ($course->published == 1) {
            $this->syncCache($course);
            $this->syncIndex($course);
        }

        $this->logger->debug('Event: {event}, Source: {source}, Data: {data}', [
            'event' => $event->getType(),
            'source' => get_class($source),
            'data' => kg_json_encode($course),
        ]);
    }

    protected function syncMaxIdCache()
    {
        $maxIdCache = new MaxCourseIdCache();

        $maxIdCache->rebuild();
    }

    protected function syncCache(CourseModel $course)
    {
        $cacheSyncer = new CourseCacheSyncer();

        $cacheSyncer->addItem($course->id);
    }

    protected function syncIndex(CourseModel $course)
    {
        $indexSyncer = new CourseIndexSyncer();

        $indexSyncer->addItem($course->id);
    }

}