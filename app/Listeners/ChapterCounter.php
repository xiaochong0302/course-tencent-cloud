<?php

namespace App\Listeners;

use App\Caches\ChapterCounter as CacheChapterCounter;
use App\Models\User as ChapterModel;
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
    }

    public function decrUserCount(Event $event, $source, ChapterModel $chapter)
    {
        $this->counter->hDecrBy($chapter->id, 'user_count');
    }

    public function incrCommentCount(Event $event, $source, ChapterModel $chapter)
    {
        $this->counter->hIncrBy($chapter->id, 'comment_count');
    }

    public function decrCommentCount(Event $event, $source, ChapterModel $chapter)
    {
        $this->counter->hDecrBy($chapter->id, 'comment_count');
    }

    public function incrAgreeCount(Event $event, $source, ChapterModel $chapter)
    {
        $this->counter->hIncrBy($chapter->id, 'agree_count');
    }

    public function decrAgreeCount(Event $event, $source, ChapterModel $chapter)
    {
        $this->counter->hDecrBy($chapter->id, 'agree_count');
    }

    public function incrOpposeCount(Event $event, $source, ChapterModel $chapter)
    {
        $this->counter->hIncrBy($chapter->id, 'oppose_count');
    }

    public function decrOpposeCount(Event $event, $source, ChapterModel $chapter)
    {
        $this->counter->hDecrBy($chapter->id, 'oppose_count');
    }

    public function incrLessonCount(Event $event, $source, ChapterModel $chapter)
    {
        $this->counter->hIncrBy($chapter->id, 'lesson_count');
    }

    public function decrLessonCount(Event $event, $source, ChapterModel $chapter)
    {
        $this->counter->hDecrBy($chapter->id, 'lesson_count');
    }

}