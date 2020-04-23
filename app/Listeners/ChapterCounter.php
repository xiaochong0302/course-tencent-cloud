<?php

namespace App\Listeners;

use App\Caches\ChapterCounter as CacheChapterCounter;
use App\Models\Chapter as ChapterModel;
use App\Services\Syncer\ChapterCounter as ChapterCounterSyncer;
use Phalcon\Events\Event;

class ChapterCounter extends Listener
{

    protected $counter;

    public function __construct()
    {
        $this->counter = new CacheChapterCounter();
    }

    public function incrUserCount(Event $event, $source, ChapterModel $chapter)
    {
        $this->counter->hIncrBy($chapter->id, 'user_count');

        $this->syncChapterCounter($chapter);
    }

    public function decrUserCount(Event $event, $source, ChapterModel $chapter)
    {
        $this->counter->hDecrBy($chapter->id, 'user_count');

        $this->syncChapterCounter($chapter);
    }

    public function incrCommentCount(Event $event, $source, ChapterModel $chapter)
    {
        $this->counter->hIncrBy($chapter->id, 'comment_count');

        $this->syncChapterCounter($chapter);
    }

    public function decrCommentCount(Event $event, $source, ChapterModel $chapter)
    {
        $this->counter->hDecrBy($chapter->id, 'comment_count');

        $this->syncChapterCounter($chapter);
    }

    public function incrAgreeCount(Event $event, $source, ChapterModel $chapter)
    {
        $this->counter->hIncrBy($chapter->id, 'agree_count');

        $this->syncChapterCounter($chapter);
    }

    public function decrAgreeCount(Event $event, $source, ChapterModel $chapter)
    {
        $this->counter->hDecrBy($chapter->id, 'agree_count');

        $this->syncChapterCounter($chapter);
    }

    public function incrOpposeCount(Event $event, $source, ChapterModel $chapter)
    {
        $this->counter->hIncrBy($chapter->id, 'oppose_count');

        $this->syncChapterCounter($chapter);
    }

    public function decrOpposeCount(Event $event, $source, ChapterModel $chapter)
    {
        $this->counter->hDecrBy($chapter->id, 'oppose_count');

        $this->syncChapterCounter($chapter);
    }

    public function incrLessonCount(Event $event, $source, ChapterModel $chapter)
    {
        $this->counter->hIncrBy($chapter->id, 'lesson_count');

        $this->syncChapterCounter($chapter);
    }

    public function decrLessonCount(Event $event, $source, ChapterModel $chapter)
    {
        $this->counter->hDecrBy($chapter->id, 'lesson_count');

        $this->syncChapterCounter($chapter);
    }

    protected function syncChapterCounter(ChapterModel $chapter)
    {
        $syncer = new ChapterCounterSyncer();

        $syncer->addItem($chapter->id);
    }

}