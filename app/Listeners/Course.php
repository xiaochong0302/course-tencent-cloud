<?php

namespace App\Listeners;

use App\Models\Course as CourseModel;
use App\Services\CourseIndexSyncer;
use Phalcon\Events\Event;

class Course extends Listener
{

    protected $logger;

    public function __construct()
    {
        $this->logger = $this->getLogger();
    }

    public function afterCreate(Event $event, $source, CourseModel $course)
    {
        $this->logger->debug('Event: {event}, Source: {source}, Data: {data}', [
            'event' => $event->getType(),
            'source' => get_class($source),
            'data' => kg_json_encode($course),
        ]);
    }

    public function afterUpdate(Event $event, $source, CourseModel $course)
    {
        if ($course->published == 1) {
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
            $this->syncIndex($course);
        }

        $this->logger->debug('Event: {event}, Source: {source}, Data: {data}', [
            'event' => $event->getType(),
            'source' => get_class($source),
            'data' => kg_json_encode($course),
        ]);
    }

    protected function syncIndex(CourseModel $course)
    {
        $cacheSyncer = new CourseIndexSyncer();
        $cacheSyncer->addItem($course->id);
    }

}