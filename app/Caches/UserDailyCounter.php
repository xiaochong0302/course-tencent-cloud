<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

class UserDailyCounter extends Counter
{

    /**
     * @var int
     */
    protected int $lifetime = 86400;

    public function getKey($id = null): string
    {
        return "user-daily-counter-{$id}";
    }

    public function getContent($id = null): array
    {
        return [
            'review_count' => 0,
            'order_count' => 0,
            'chapter_like_count' => 0,
            'review_like_count' => 0,
        ];
    }

}
