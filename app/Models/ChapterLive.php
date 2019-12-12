<?php

namespace App\Models;

class ChapterLive extends Model
{

    /**
     * 主键编号
     *
     * @var integer
     */
    public $id;

    /**
     * 课程编号
     *
     * @var integer
     */
    public $course_id;

    /**
     * 章节编号
     *
     * @var integer
     */
    public $chapter_id;

    /**
     * 开始时间
     *
     * @var integer
     */
    public $start_time;

    /**
     * 结束时间
     *
     * @var integer
     */
    public $end_time;

    /**
     * 用户限额
     *
     * @var integer
     */
    public $user_limit;

    /**
     * 创建时间
     *
     * @var integer
     */
    public $created_at;

    /**
     * 更新时间
     *
     * @var integer
     */
    public $updated_at;

    public function getSource()
    {
        return 'chapter_live';
    }

    public function beforeCreate()
    {
        $this->created_at = time();
    }

    public function beforeUpdate()
    {
        $this->updated_at = time();
    }

}
