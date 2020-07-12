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

    public function incrCommentLikeCount(Event $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'comment_like_count');
    }

    public function incrConsultLikeCount(Event $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'consult_like_count');
    }

    public function incrChapterLikeCount(Event $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'chapter_like_count');
    }

    public function incrReviewLikeCount(Event $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'review_like_count');
    }

}