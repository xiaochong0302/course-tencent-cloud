<?php

namespace App\Models;

class ChapterRead extends Model
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
     * 内容
     *
     * @var string
     */
    public $content;

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
        return 'kg_chapter_read';
    }

    public function beforeCreate()
    {
        $this->create_time = time();

        /**
         * text类型不能填充默认值
         */
        if (empty($this->content)) {
            $this->content = '';
        }
    }

    public function beforeUpdate()
    {
        $this->update_time = time();
    }

}
