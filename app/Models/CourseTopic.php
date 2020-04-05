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
    public $create_time;

    public function getSource()
    {
        return 'kg_course_topic';
    }

    public function beforeCreate()
    {
        $this->create_time = time();
    }

}
