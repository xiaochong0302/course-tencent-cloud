<?php

namespace App\Listeners;

use App\Caches\Chapter as ChapterCache;
use App\Caches\MaxChapterId as MaxChapterIdCache;
use App\Models\Chapter as ChapterModel;
use Phalcon\Events\Event;

class ChapterAdmin extends Listener
{

    protected $logger;

    public function __construct()
    {
        $this->logger = $this->getLogger();
    }

    public function afterCreate(Event $event, $source, ChapterModel $chapter)
    {
        $this->syncMaxIdCache();

        $this->logger->debug('Event: {event}, Source: {source}, Data: {data}', [
            'event' => $event->getType(),
            'source' => get_class($source),
            'data' => kg_json_encode($chapter),
        ]);
    }

    public function afterUpdate(Event $event, $source, ChapterModel $chapter)
    {
        if ($chapter->published == 1) {
            $this->syncCache($chapter);
        }

        $this->logger->debug('Event: {event}, Source: {source}, Data: {data}', [
            'event' => $event->getType(),
            'source' => get_class($source),
            'data' => kg_json_encode($chapter),
        ]);
    }

    public function afterDelete(Event $event, $source, ChapterModel $chapter)
    {
        if ($chapter->published == 1) {
            $this->syncCache($chapter);
        }

        $this->logger->debug('Event: {event}, Source: {source}, Data: {data}', [
            'event' => $event->getType(),
            'source' => get_class($source),
            'data' => kg_json_encode($chapter),
        ]);
    }

    public function afterRestore(Event $event, $source, ChapterModel $chapter)
    {
        if ($chapter->published == 1) {
            $this->syncCache($chapter);
        }

        $this->logger->debug('Event: {event}, Source: {source}, Data: {data}', [
            'event' => $event->getType(),
            'source' => get_class($source),
            'data' => kg_json_encode($chapter),
        ]);
    }

    protected function syncMaxIdCache()
    {
        $maxIdCache = new MaxChapterIdCache();

        $maxIdCache->rebuild();
    }

    protected function syncCache(ChapterModel $chapter)
    {
        $chapterCache = new ChapterCache();

        $chapterCache->rebuild($chapter->id);
    }

}