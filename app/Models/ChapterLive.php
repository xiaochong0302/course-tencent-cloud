<?php

namespace App\Models;

class ChapterLive extends Model
{

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
        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        $this->update_time = time();
    }

}
