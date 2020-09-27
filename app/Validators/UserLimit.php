<?php

namespace App\Validators;

use App\Caches\UserDailyCounter as CacheUserDailyCounter;
use App\Exceptions\BadRequest as BadRequestException;
use App\Models\User as UserModel;

class UserLimit extends Validator
{

    protected $counter;

    public function __construct()
    {
        $this->counter = new CacheUserDailyCounter();
    }

    public function checkFavoriteLimit(UserModel $user)
    {
        $limit = $user->vip ? 1000 : 500;

        if ($user->favorite_count > $limit) {
            throw new BadRequestException('user_limit.reach_favorite_limit');
        }
    }

    public function checkDailyDanmuLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'danmu_count');

        $limit = $user->vip ? 100 : 50;

        if ($count > $limit) {
            throw new BadRequestException('user_limit.reach_daily_danmu_limit');
        }
    }

    public function checkDailyConsultLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'consult_count');

        $limit = $user->vip ? 20 : 10;

        if ($count > $limit) {
            throw new BadRequestException('user_limit.reach_daily_consult_limit');
        }
    }

    public function checkDailyOrderLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'order_count');

        if ($count > 10) {
            throw new BadRequestException('user_limit.reach_daily_order_limit');
        }
    }

    public function checkDailyChapterLikeLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'chapter_like_count');

        $limit = $user->vip ? 200 : 100;

        if ($count > $limit) {
            throw new BadRequestException('user_limit.reach_daily_like_limit');
        }
    }

    public function checkDailyConsultLikeLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'consult_like_count');

        $limit = $user->vip ? 200 : 100;

        if ($count > $limit) {
            throw new BadRequestException('user_limit.reach_daily_like_limit');
        }
    }

    public function checkDailyReviewLikeLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'review_like_count');

        $limit = $user->vip ? 200 : 100;

        if ($count > $limit) {
            throw new BadRequestException('user_limit.reach_daily_like_limit');
        }
    }

}
