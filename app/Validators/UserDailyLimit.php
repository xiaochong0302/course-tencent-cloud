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

    public function checkChapterVoteLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'chapter_vote_count');

        $limit = $user->vip ? 200 : 100;

        if ($count > $limit) {
            throw new BadRequestException('user_daily_limit.reach_vote_limit');
        }
    }

    public function checkCommentVoteLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'comment_vote_count');

        $limit = $user->vip ? 200 : 100;

        if ($count > $limit) {
            throw new BadRequestException('user_daily_limit.reach_vote_limit');
        }
    }

    public function checkConsultVoteLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'consult_vote_count');

        $limit = $user->vip ? 200 : 100;

        if ($count > $limit) {
            throw new BadRequestException('user_daily_limit.reach_vote_limit');
        }
    }

    public function checkReviewVoteLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'review_vote_count');

        $limit = $user->vip ? 200 : 100;

        if ($count > $limit) {
            throw new BadRequestException('user_daily_limit.reach_vote_limit');
        }
    }

}
