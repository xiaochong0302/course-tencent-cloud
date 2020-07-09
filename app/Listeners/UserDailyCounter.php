<?php

namespace App\Listeners;

use App\Caches\UserDailyCounter as CacheUserDailyCounter;
use App\Models\User as UserModel;
use Phalcon\Events\Event;

class UserDailyCounter extends Listener
{

    protected $counter;

    public function __construct()
    {
        $this->counter = new CacheUserDailyCounter();
    }

    public function incrFavoriteCount(Event $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'favorite_count');
    }

    public function incrCommentCount(Event $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'comment_count');
    }

    public function incrDanmuCount(Event $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'danmu_count');
    }

    public function incrConsultCount(Event $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'consult_count');
    }

    public function incrReviewCount(Event $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'review_count');
    }

    public function incrOrderCount(Event $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'order_count');
    }

    public function incrCommentVoteCount(Event $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'comment_vote_count');
    }

    public function incrConsultVoteCount(Event $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'consult_vote_count');
    }

    public function incrChapterVoteCount(Event $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'chapter_vote_count');
    }

    public function incrReviewVoteCount(Event $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'review_vote_count');
    }

}