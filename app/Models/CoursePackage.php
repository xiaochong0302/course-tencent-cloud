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
    public $created_at;

    public function getSource()
    {
        return 'course_package';
    }

    public function beforeCreate()
    {
        $this->created_at = time();
    }

}
