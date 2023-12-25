<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

class UserDailyCounter extends Counter
{

    public function getLifetime()
    {
        $tomorrow = strtotime('tomorrow');

        return $tomorrow - time();
    }

    public function getKey($id = null)
    {
        return "user_daily_counter:{$id}";
    }

    public function getContent($id = null)
    {
        return [
            'article_count' => 0,
            'question_count' => 0,
            'answer_count' => 0,
            'comment_count' => 0,
            'consult_count' => 0,
            'order_count' => 0,
            'chapter_like_count' => 0,
            'consult_like_count' => 0,
            'review_like_count' => 0,
            'article_like_count' => 0,
            'question_like_count' => 0,
            'answer_like_count' => 0,
            'comment_like_count' => 0,
        ];
    }

}
