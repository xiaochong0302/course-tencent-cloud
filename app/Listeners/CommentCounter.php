<?php

namespace App\Listeners;

use App\Caches\CommentCounter as CacheCommentCounter;
use App\Models\Comment as CommentModel;
use App\Services\Syncer\CommentCounter as CommentCounterSyncer;
use Phalcon\Events\Event;

class CommentCounter extends Listener
{

    protected $counter;

    public function __construct()
    {
        $this->counter = new CacheCommentCounter();
    }

    public function incrReplyCount(Event $event, $source, CommentModel $comment)
    {
        $this->counter->hIncrBy($comment->id, 'reply_count');

        $this->syncCommentCounter($comment);
    }

    public function decrReplyCount(Event $event, $source, CommentModel $comment)
    {
        $this->counter->hDecrBy($comment->id, 'reply_count');

        $this->syncCommentCounter($comment);
    }

    public function incrLikeCount(Event $event, $source, CommentModel $comment)
    {
        $this->counter->hIncrBy($comment->id, 'like_count');

        $this->syncCommentCounter($comment);
    }

    public function decrLikeCount(Event $event, $source, CommentModel $comment)
    {
        $this->counter->hDecrBy($comment->id, 'like_count');

        $this->syncCommentCounter($comment);
    }

    protected function syncCommentCounter(CommentModel $comment)
    {
        $syncer = new CommentCounterSyncer();

        $syncer->addItem($comment->id);
    }

}