<?php

namespace App\Validators;

use App\Caches\UserDailyCounter as CacheUserDailyCounter;
use App\Exceptions\BadRequest as BadRequestException;
use App\Models\User as UserModel;

class UserDailyLimit extends Validator
{

    protected $counter;

    public function __construct()
    {
        $this->counter = new CacheUserDailyCounter();
    }

    public function checkFavoriteLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'favorite_count');

        $limit = $user->vip ? 100 : 50;

        if ($count > $limit) {
            throw new BadRequestException('user_daily_limit.reach_favorite_limit');
        }
    }

    public function checkCommentLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'comment_count');

        $limit = $user->vip ? 100 : 50;

        if ($count > $limit) {
            throw new BadRequestException('user_daily_limit.reach_comment_limit');
        }
    }

    public function checkDanmuLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'danmu_count');

        $limit = $user->vip ? 100 : 50;

        if ($count > $limit) {
            throw new BadRequestException('user_daily_limit.reach_danmu_limit');
        }
    }

    public function checkConsultLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'consult_count');

        $limit = $user->vip ? 20 : 10;

        if ($count > $limit) {
            throw new BadRequestException('user_daily_limit.reach_consult_limit');
        }
    }

    public function checkReviewLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'review_count');

        if ($count > 10) {
            throw new BadRequestException('user_daily_limit.reach_review_limit');
        }
    }

    public function checkOrderLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'order_count');

        if ($count > 10) {
            throw new BadRequestException('user_daily_limit.reach_order_limit');
        }
    }

    public function checkChapterLikeLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'chapter_like_count');

        $limit = $user->vip ? 200 : 100;

        if ($count > $limit) {
            throw new BadRequestException('user_daily_limit.reach_like_limit');
        }
    }

    public function checkCommentLikeLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'comment_like_count');

        $limit = $user->vip ? 200 : 100;

        if ($count > $limit) {
            throw new BadRequestException('user_daily_limit.reach_like_limit');
        }
    }

    public function checkConsultLikeLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'consult_like_count');

        $limit = $user->vip ? 200 : 100;

        if ($count > $limit) {
            throw new BadRequestException('user_daily_limit.reach_like_limit');
        }
    }

    public function checkReviewLikeLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'review_like_count');

        $limit = $user->vip ? 200 : 100;

        if ($count > $limit) {
            throw new BadRequestException('user_daily_limit.reach_like_limit');
        }
    }

}
