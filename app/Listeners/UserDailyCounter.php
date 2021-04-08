<?php

namespace App\Listeners;

use App\Caches\UserDailyCounter as CacheUserDailyCounter;
use App\Models\User as UserModel;
use Phalcon\Events\Event as PhEvent;

class UserDailyCounter extends Listener
{

    protected $counter;

    public function __construct()
    {
        $this->counter = new CacheUserDailyCounter();
    }

    public function incrFavoriteCount(PhEvent $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'favorite_count');
    }

    public function incrCommentCount(PhEvent $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'comment_count');
    }

    public function incrDanmuCount(PhEvent $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'danmu_count');
    }

    public function incrConsultCount(PhEvent $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'consult_count');
    }

    public function incrReviewCount(PhEvent $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'review_count');
    }

    public function incrOrderCount(PhEvent $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'order_count');
    }

    public function incrConsultLikeCount(PhEvent $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'consult_like_count');
    }

    public function incrChapterLikeCount(PhEvent $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'chapter_like_count');
    }

    public function incrReviewLikeCount(PhEvent $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'review_like_count');
    }

    public function incrArticleLikeCount(PhEvent $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'article_like_count');
    }

}