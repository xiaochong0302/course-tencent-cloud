<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Consult extends Model
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
     * 用户编号
     *
     * @var int
     */
    public $user_id;

    /**
     * 提问
     *
     * @var string
     */
    public $question;

    /**
     * 回答
     *
     * @var string
     */
    public $answer;

    /**
     * 赞成数
     *
     * @var int
     */
    public $like_count;

    /**
     * 私密标识
     *
     * @var int
     */
    public $private;

    /**
     * 发布标识
     *
     * @var int
     */
    public $published;

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted;

    /**
     * 创建时间
     *
     * @var int
     */
    public $create_time;

    /**
     * 更新时间
     *
     * @var int
     */
    public $update_time;

    public function getSource(): string
    {
        return 'kg_consult';
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
        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        $this->update_time = time();

        if ($this->deleted == 1) {
            $this->published = 0;
        }
    }

}
