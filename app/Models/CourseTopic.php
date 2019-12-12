<?php

namespace App\Models;

class CourseTopic extends Model
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
     * 话题编号
     *
     * @var integer
     */
    public $topic_id;

    /**
     * 创建时间
     * 
     * @var integer
     */
    public $created_at;

    public function getSource()
    {
        return 'course_topic';
    }

    public function beforeCreate()
    {
        $this->created_at = time();
    }

}
