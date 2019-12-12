<?php

namespace App\Models;

class ChapterVod extends Model
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
     * 文件编号
     *
     * @var integer
     */
    public $file_id;

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
        return 'chapter_vod';
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
