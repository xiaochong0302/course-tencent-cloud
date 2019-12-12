<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Review extends Model
{

    /**
     * 主键编号
     * 
     * @var integer
     */
    public $id;

    /**
     * 用户编号
     * 
     * @var integer
     */
    public $user_id;

    /**
     * 课程编号
     * 
     * @var integer
     */
    public $course_id;

    /**
     * 课程评分
     * 
     * @var integer
     */
    public $rating;

    /**
     * 评价内容
     * 
     * @var string
     */
    public $content;

    /**
     * 发布标识
     *
     * @var integer
     */
    public $published;

    /**
     * 删除标识
     * 
     * @var integer
     */
    public $deleted;

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
        return 'review';
    }

    public function initialize()
    {
        parent::initialize();

        $this->addBehavior(
            new SoftDelete([
                'field' => 'deleted',
                'value' => 1,
            ])
        );
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
