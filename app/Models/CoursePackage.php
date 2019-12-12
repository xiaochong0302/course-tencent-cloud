<?php

namespace App\Models;

class CoursePackage extends Model
{

    /**
     * 主键编号
     * 
     * @var integer
     */
    public $id;

    /**
     * 套餐编号
     * 
     * @var integer
     */
    public $package_id;

    /**
     * 课程编号
     * 
     * @var integer
     */
    public $course_id;

    /**
     * 创建时间
     * 
     * @var integer
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
