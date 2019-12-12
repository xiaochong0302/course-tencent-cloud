<?php

namespace App\Models;

class CourseRelated extends Model
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
     * 相关编号
     * 
     * @var integer
     */
    public $related_id;

    /**
     * 创建时间
     * 
     * @var integer
     */
    public $created_at;

    public function getSource()
    {
        return 'course_related';
    }

    public function beforeCreate()
    {
        $this->created_at = time();
    }

}
