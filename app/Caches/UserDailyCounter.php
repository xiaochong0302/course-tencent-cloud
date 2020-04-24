<?php

namespace App\Caches;

class UserDailyCounter extends Counter
{

    protected $lifetime = 1 * 86400;

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
            'favorite_count' => 0,
            'comment_count' => 0,
            'consult_count' => 0,
            'order_count' => 0,
            'chapter_vote_count' => 0,
            'comment_vote_count' => 0,
            'consult_vote_count' => 0,
            'review_vote_count' => 0,
        ];
    }

}
