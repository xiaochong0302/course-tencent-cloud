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

    public function checkDailyReportLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'report_count');

        $limit = $user->vip ? 100 : 50;

        if ($count > $limit) {
            throw new BadRequestException('user_limit.reach_daily_report_limit');
        }
    }

    public function checkDailyArticleLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'article_count');

        $limit = $user->vip ? 10 : 5;

        if ($count > $limit) {
            throw new BadRequestException('user_limit.reach_daily_article_limit');
        }
    }

    public function checkDailyQuestionLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'question_count');

        $limit = $user->vip ? 10 : 5;

        if ($count > $limit) {
            throw new BadRequestException('user_limit.reach_daily_question_limit');
        }
    }

    public function checkDailyAnswerLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'answer_count');

        $limit = $user->vip ? 20 : 10;

        if ($count > $limit) {
            throw new BadRequestException('user_limit.reach_daily_answer_limit');
        }
    }

    public function checkDailyCommentLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'comment_count');

        $limit = $user->vip ? 100 : 50;

        if ($count > $limit) {
            throw new BadRequestException('user_limit.reach_daily_comment_limit');
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

        if ($count > 50) {
            throw new BadRequestException('user_limit.reach_daily_order_limit');
        }
    }

    public function checkDailyArticleLikeLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'article_like_count');

        $limit = $user->vip ? 100 : 50;

        if ($count > $limit) {
            throw new BadRequestException('user_limit.reach_daily_like_limit');
        }
    }

    public function checkDailyQuestionLikeLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'question_like_count');

        $limit = $user->vip ? 100 : 50;

        if ($count > $limit) {
            throw new BadRequestException('user_limit.reach_daily_like_limit');
        }
    }

    public function checkDailyAnswerLikeLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'answer_like_count');

        $limit = $user->vip ? 100 : 50;

        if ($count > $limit) {
            throw new BadRequestException('user_limit.reach_daily_like_limit');
        }
    }

    public function checkDailyChapterLikeLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'chapter_like_count');

        $limit = $user->vip ? 100 : 50;

        if ($count > $limit) {
            throw new BadRequestException('user_limit.reach_daily_like_limit');
        }
    }

    public function checkDailyConsultLikeLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'consult_like_count');

        $limit = $user->vip ? 100 : 50;

        if ($count > $limit) {
            throw new BadRequestException('user_limit.reach_daily_like_limit');
        }
    }

    public function checkDailyReviewLikeLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'review_like_count');

        $limit = $user->vip ? 100 : 50;

        if ($count > $limit) {
            throw new BadRequestException('user_limit.reach_daily_like_limit');
        }
    }

    public function checkDailyCommentLikeLimit(UserModel $user)
    {
        $count = $this->counter->hGet($user->id, 'comment_like_count');

        $limit = $user->vip ? 200 : 100;

        if ($count > $limit) {
            throw new BadRequestException('user_limit.reach_daily_like_limit');
        }
    }

}
