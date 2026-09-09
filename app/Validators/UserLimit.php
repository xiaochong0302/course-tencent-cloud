<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Caches\UserDailyCounter as CacheUserDailyCounter;
use App\Exceptions\BadRequest as BadRequestException;
use App\Models\User as UserModel;

class UserLimit extends Validator
{

    protected CacheUserDailyCounter $counter;

    public function __construct()
    {
        $this->counter = new CacheUserDailyCounter();
    }

    public function checkFavoriteLimit(UserModel $user): void
    {
        $limit = $user->vip ? 1000 : 500;

        if ($user->favorite_count > $limit) {
            throw new BadRequestException('user_limit.reach_favorite_limit');
        }
    }

    public function checkDailyOrderLimit(UserModel $user): void
    {
        $count = $this->counter->hGet($user->id, 'order_count');

        if ($count > 50) {
            throw new BadRequestException('user_limit.reach_daily_order_limit');
        }
    }

    public function checkDailyChapterLikeLimit(UserModel $user): void
    {
        $count = $this->counter->hGet($user->id, 'chapter_like_count');

        $limit = $user->vip ? 100 : 50;

        if ($count > $limit) {
            throw new BadRequestException('user_limit.reach_daily_like_limit');
        }
    }

    public function checkDailyReviewLikeLimit(UserModel $user): void
    {
        $count = $this->counter->hGet($user->id, 'review_like_count');

        $limit = $user->vip ? 100 : 50;

        if ($count > $limit) {
            throw new BadRequestException('user_limit.reach_daily_like_limit');
        }
    }

}
