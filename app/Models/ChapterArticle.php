<?php

namespace App\Models;

class ChapterArticle extends Model
{

    /**
     * 格式类型
     */
    const FORMAT_HTML = 1;
    const FORMAT_MARKDOWN = 2;

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
     * 内容
     *
     * @var string
     */
    public $content;

    /**
     * 格式
     *
     * @var integer
     */
    public $format;

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
        return 'chapter_article';
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
