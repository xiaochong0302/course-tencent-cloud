<?php

namespace App\Models;

class CourseFavorite extends Model
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
     * 用户编号
     * 
     * @var integer
     */
    public $user_id;

    /**
     * 创建时间
     * 
     * @var integer
     */
    public $created_at;

    public function getSource()
    {
        return 'course_favorite';
    }

    public function beforeCreate()
    {
        $this->created_at = time();
    }

}
