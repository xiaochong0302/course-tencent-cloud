<?php

namespace App\Models;

class CourseRelated extends Model
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
     * 相关编号
     *
     * @var int
     */
    public $related_id;

    /**
     * 创建时间
     *
     * @var int
     */
    public $created_at;

    public function getSource()
    {
        return 'kg_course_related';
    }

    public function beforeCreate()
    {
        $this->created_at = time();
    }

}
