<?php

namespace App\Listeners;

use App\Caches\ReviewCounter as CacheReviewCounter;
use App\Models\Review as ReviewModel;
use App\Services\Syncer\ReviewCounter as ReviewCounterSyncer;
use Phalcon\Events\Event;

class ReviewCounter extends Listener
{

    protected $counter;

    public function __construct()
    {
        $this->counter = new CacheReviewCounter();
    }

    public function incrLikeCount(Event $event, $source, ReviewModel $review)
    {
        $this->counter->hIncrBy($review->id, 'like_count');

        $this->syncReviewCounter($review);
    }

    public function decrLikeCount(Event $event, $source, ReviewModel $review)
    {
        $this->counter->hDecrBy($review->id, 'like_count');

        $this->syncReviewCounter($review);
    }

    protected function syncReviewCounter(ReviewModel $review)
    {
        $syncer = new ReviewCounterSyncer();

        $syncer->addItem($review->id);
    }

}