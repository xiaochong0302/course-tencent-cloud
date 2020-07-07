<?php

namespace App\Models;

class ChapterRead extends Model
{

    /**
     * 格式类型
     */
    const FORMAT_HTML = 'html';
    const FORMAT_MARKDOWN = 'markdown';

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
     * 格式
     *
     * @var string
     */
    public $format;

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
    }

    public function beforeUpdate()
    {
        $this->update_time = time();
    }

    public static function formatTypes()
    {
        return [
            self::FORMAT_HTML => 'html',
            self::FORMAT_MARKDOWN => 'markdown',
        ];
    }

}
