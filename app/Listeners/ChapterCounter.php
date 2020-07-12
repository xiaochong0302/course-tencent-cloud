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

    public function incrLikeCount(Event $event, $source, ChapterModel $chapter)
    {
        $this->counter->hIncrBy($chapter->id, 'like_count');

        $this->syncChapterCounter($chapter);
    }

    public function decrLikeCount(Event $event, $source, ChapterModel $chapter)
    {
        $this->counter->hDecrBy($chapter->id, 'like_count');

        $this->syncChapterCounter($chapter);
    }

    protected function syncChapterCounter(ChapterModel $chapter)
    {
        $syncer = new ChapterCounterSyncer();

        $syncer->addItem($chapter->id);
    }

}