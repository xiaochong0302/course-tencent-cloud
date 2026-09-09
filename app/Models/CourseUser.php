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
     * 主键编号
     *
     * @var int|null
     */
    public ?int $id = null;

    /**
     * 课程编号
     *
     * @var int
     */
    public int $course_id = 0;

    /**
     * 用户编号
     *
     * @var int
     */
    public int $user_id = 0;

    /**
     * 计划编号
     *
     * @var int
     */
    public int $plan_id = 0;

    /**
     * 来源类型
     *
     * @var int
     */
    public int $source_type = 0;

    /**
     * 过期时间
     *
     * @var int
     */
    public int $expiry_time = 0;

    /**
     * 学习时长（秒）
     *
     * @var int
     */
    public int $duration = 0;

    /**
     * 学习进度（％）
     *
     * @var int
     */
    public int $progress = 0;

    /**
     * 评价标识
     *
     * @var int
     */
    public int $reviewed = 0;

    /**
     * 删除标识
     *
     * @var int
     */
    public int $deleted = 0;

    /**
     * 活跃时间
     *
     * @var int
     */
    public int $active_time = 0;

    /**
     * 创建时间
     *
     * @var int
     */
    public int $create_time = 0;

    /**
     * 更新时间
     *
     * @var int
     */
    public int $update_time = 0;

    public function initialize(): void
    {
        parent::initialize();

        $this->setSource('kg_course_user');
    }

    public function beforeCreate(): void
    {
        $this->plan_id = (int)date('Ymd');

        $this->create_time = time();
    }

    public function beforeUpdate(): void
    {
        $this->update_time = time();
    }

    public static function sourceTypes(): array
    {
        return KgOwnership::sourceTypes();
    }

}
