<?php

namespace App\Models;

class ChapterUser extends Model
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
     * 章节编号
     *
     * @var integer
     */
    public $chapter_id;

    /**
     * 用户编号
     * 
     * @var integer
     */
    public $user_id;

    /**
     * 学习时长
     *
     * @var integer
     */
    public $duration;

    /**
     * 播放位置
     *
     * @var integer
     */
    public $position;

    /**
     * 完成标识
     *
     * @var integer
     */
    public $finished;

    /**
     * 收藏标识
     *
     * @var integer
     */
    public $favorited;

    /**
     * 喜欢标识
     *
     * @var integer
     */
    public $liked;

    /**
     * 创建时间
     * 
     * @var integer
     */
    public $created_at;

    /**
     * 更新时间
     * 
     * @var integer
     */
    public $updated_at;

    public function getSource()
    {
        return 'chapter_user';
    }

    public function beforeCreate()
    {
        $this->created_at = time();
    }

    public function beforeUpdate()
    {
        $this->updated_at = time();
    }

}
