<?php

namespace App\Models;

class ChapterLive extends Model
{

    /**
     * 状态类型
     */
    const STATUS_ACTIVE = 1; // 活跃
    const STATUS_INACTIVE = 2; // 静默
    const STATUS_FORBID = 3; // 禁播

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 课程编号
     *
     * @var int
     */
    public $course_id;

    /**
     * 章节编号
     *
     * @var int
     */
    public $chapter_id;

    /**
     * 开始时间
     *
     * @var int
     */
    public $start_time;

    /**
     * 结束时间
     *
     * @var int
     */
    public $end_time;

    /**
     * 用户限额
     *
     * @var int
     */
    public $user_limit;

    /**
     * 直播状态
     *
     * @var int
     */
    public $status;

    /**
     * 创建时间
     *
     * @var int
     */
    public $create_time;

    /**
     * 更新时间
     *
     * @var int
     */
    public $update_time;

    public function getSource(): string
    {
        return 'kg_chapter_live';
    }

    public function beforeCreate()
    {
        $this->status = self::STATUS_INACTIVE;

        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        $this->update_time = time();
    }

    public static function generateStreamName($id)
    {
        return "chapter_{$id}";
    }

    public static function parseFromStreamName($streamName)
    {
        return str_replace('chapter_', '', $streamName);
    }

}
