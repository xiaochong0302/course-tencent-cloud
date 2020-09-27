<?php

namespace App\Models;

class CourseCategory extends Model
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
     * 分类编号
     *
     * @var int
     */
    public $category_id;

    /**
     * 创建时间
     *
     * @var int
     */
    public $create_time;

    public function getSource(): string
    {
        return 'kg_course_category';
    }

    public function beforeCreate()
    {
        $this->create_time = time();
    }

}
