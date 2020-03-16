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
    public $created_at;

    /**
     * 更新时间
     *
     * @var int
     */
    public $updated_at;

    public function getSource()
    {
        return 'kg_chapter_read';
    }

    public function beforeCreate()
    {
        $this->created_at = time();
    }

    public function beforeUpdate()
    {
        $this->updated_at = time();
    }

    public static function formatTypes()
    {
        $list = [
            self::FORMAT_HTML => 'html',
            self::FORMAT_MARKDOWN => 'format',
        ];

        return $list;
    }

}
