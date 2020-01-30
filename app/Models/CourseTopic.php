<?php

namespace App\Models;

class CourseTopic extends Model
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
     * 话题编号
     *
     * @var int
     */
    public $topic_id;

    /**
     * 创建时间
     *
     * @var int
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
