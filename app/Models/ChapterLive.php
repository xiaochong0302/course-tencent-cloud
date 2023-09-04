<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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
    public $id = 0;

    /**
     * 课程编号
     *
     * @var int
     */
    public $course_id = 0;

    /**
     * 章节编号
     *
     * @var int
     */
    public $chapter_id = 0;

    /**
     * 开始时间
     *
     * @var int
     */
    public $start_time = 0;

    /**
     * 结束时间
     *
     * @var int
     */
    public $end_time = 0;

    /**
     * 用户限额
     *
     * @var int
     */
    public $user_limit = 0;

    /**
     * 直播状态
     *
     * @var int
     */
    public $status = 0;

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
        if ($id == 'test') return $id;

        return sprintf('chapter-%03d', $id);
    }

    public static function parseFromStreamName($streamName)
    {
        if ($streamName == 'test') return $streamName;

        return (int)str_replace('chapter-', '', $streamName);
    }

}
