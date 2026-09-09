<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

class KgOwnership extends Model
{

    /**
     * 来源类型
     */
    const int SOURCE_FREE = 1; // 免费
    const int SOURCE_CHARGE = 2; // 付费
    const int SOURCE_VIP = 3; // 畅学
    const int SOURCE_MANUAL = 4; // 分配
    const int SOURCE_POINT_REDEEM = 5; // 积分
    const int SOURCE_LUCKY_REDEEM = 6; // 抽奖
    const int SOURCE_TEACHER = 7; // 教师
    const int SOURCE_GROUP = 8; // 分组
    const int SOURCE_TRIAL = 10; // 试听

    public static function sourceTypes(): array
    {
        return [
            self::SOURCE_FREE => '免费',
            self::SOURCE_CHARGE => '付费',
            self::SOURCE_TRIAL => '试听',
            self::SOURCE_VIP => '畅学',
            self::SOURCE_MANUAL => '分配',
            self::SOURCE_TEACHER => '教师',
        ];
    }

}
