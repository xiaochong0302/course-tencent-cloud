<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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

    public function incrReportCount(PhEvent $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'report_count');
    }

    public function incrArticleCount(PhEvent $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'article_count');
    }

    public function incrQuestionCount(PhEvent $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'question_count');
    }

    public function incrAnswerCount(PhEvent $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'answer_count');
    }

    public function incrCommentCount(PhEvent $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'comment_count');
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

    public function incrQuestionLikeCount(PhEvent $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'question_like_count');
    }

    public function incrAnswerLikeCount(PhEvent $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'answer_like_count');
    }

    public function incrCommentLikeCount(PhEvent $event, $source, UserModel $user)
    {
        $this->counter->hIncrBy($user->id, 'comment_like_count');
    }

}