<?php

namespace App\Models;

class CoursePackage extends Model
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
     * 套餐编号
     *
     * @var int
     */
    public $package_id;

    /**
     * 创建时间
     *
     * @var int
     */
    public $create_time;

    public function getSource()
    {
        return 'kg_course_package';
    }

    public function beforeCreate()
    {
        $this->create_time = time();
    }

}
