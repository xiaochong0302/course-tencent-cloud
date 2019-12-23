<?php

namespace App\Listeners;

use App\Models\Course as CourseModel;
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
            $this->syncIndexAfterUpdate($course);
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
            $this->syncIndexAfterDelete($course);
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
            $this->syncIndexAfterRestore($course);
        }

        $this->logger->debug('Event: {event}, Source: {source}, Data: {data}', [
            'event' => $event->getType(),
            'source' => get_class($source),
            'data' => kg_json_encode($course),
        ]);
    }

    protected function syncIndexAfterUpdate(CourseModel $course)
    {
        /**
         * @var \Phalcon\Cache\Backend $cache
         */
        $cache = $this->getDI()->get('cache');

        $key = $this->getSyncIndexKey($course->id);

        $content = [
            'id' => $course->id,
            'type' => 'update',
        ];

        $cache->save($key, $content, 86400);
    }

    protected function syncIndexAfterDelete($course)
    {
        /**
         * @var \Phalcon\Cache\Backend $cache
         */
        $cache = $this->getDI()->get('cache');

        $key = $this->getSyncIndexKey($course->id);

        $content = [
            'id' => $course->id,
            'type' => 'delete',
        ];

        $cache->save($key, $content, 86400);
    }

    protected function syncIndexAfterRestore($course)
    {
        /**
         * @var \Phalcon\Cache\Backend $cache
         */
        $cache = $this->getDI()->get('cache');

        $key = $this->getSyncIndexKey($course->id);

        $content = [
            'id' => $course->id,
            'type' => 'restore',
        ];

        $cache->save($key, $content, 86400);
    }

    protected function getSyncIndexKey($courseId)
    {
        $key = "sync:index:course:{$courseId}";

        return $key;
    }

}