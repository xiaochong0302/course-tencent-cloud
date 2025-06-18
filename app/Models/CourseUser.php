<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

class CourseUser extends Model
{

    /**
     * 来源类型
     */
    const SOURCE_FREE = 1; // 免费
    const SOURCE_CHARGE = 2; // 付费
    const SOURCE_VIP = 3; // 畅学
    const SOURCE_MANUAL = 4; // 分配
    const SOURCE_POINT_REDEEM = 5; // 积分兑换
    const SOURCE_LUCKY_REDEEM = 6; // 抽奖兑换
    const SOURCE_TEACHER = 7; // 教师
    const SOURCE_TRIAL = 10; // 试听

    /**
     * 主键编号
     *
     * @var int
     */
    public $id = 0;

    /**
     * 课程编号
     *
     * @var int
     */
    public $course_id = 0;

    /**
     * 用户编号
     *
     * @var int
     */
    public $user_id = 0;

    /**
     * 计划编号
     *
     * @var int
     */
    public $plan_id = 0;

    /**
     * 来源类型
     *
     * @var int
     */
    public $source_type = 0;

    /**
     * 过期时间
     *
     * @var int
     */
    public $expiry_time = 0;

    /**
     * 学习时长（秒）
     *
     * @var int
     */
    public $duration = 0;

    /**
     * 学习进度（％）
     *
     * @var int
     */
    public $progress = 0;

    /**
     * 评价标识
     *
     * @var int
     */
    public $reviewed = 0;

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted = 0;

    /**
     * 活跃时间
     *
     * @var int
     */
    public $active_time = 0;

    /**
     * 创建时间
     *
     * @var int
     */
    public $create_time = 0;

    /**
     * 更新时间
     *
     * @var int
     */
    public $update_time = 0;

    public function getSource(): string
    {
        return 'kg_course_user';
    }

    public function beforeCreate()
    {
        $this->plan_id = (int)date('Ymd');

        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        $this->update_time = time();
    }

    public static function sourceTypes()
    {
        return [
            self::SOURCE_FREE => '免费',
            self::SOURCE_CHARGE => '付费',
            self::SOURCE_TRIAL => '试听',
            self::SOURCE_VIP => '畅学',
            self::SOURCE_MANUAL => '分配',
            self::SOURCE_TEACHER => '教师',
            self::SOURCE_POINT_REDEEM => '积分兑换',
            self::SOURCE_LUCKY_REDEEM => '抽奖兑换',
        ];
    }

}
