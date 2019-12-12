<?php

namespace App\Models;

class CourseCategory extends Model
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
     * 分类编号
     *
     * @var integer
     */
    public $category_id;

    /**
     * 创建时间
     * 
     * @var integer
     */
    public $created_at;

    public function getSource()
    {
        return 'course_category';
    }

    public function beforeCreate()
    {
        $this->created_at = time();
    }

}
