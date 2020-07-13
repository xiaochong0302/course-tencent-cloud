<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Comment extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 父级编号
     *
     * @var int
     */
    public $parent_id;

    /**
     * 课程编号
     *
     * @var int
     */
    public $course_id;

    /**
     * 章节编号
     *
     * @var int
     */
    public $chapter_id;

    /**
     * 用户编号
     *
     * @var int
     */
    public $user_id;

    /**
     * 回复用户
     *
     * @var int
     */
    public $to_user_id;

    /**
     * 内容
     *
     * @var string
     */
    public $content;

    /**
     * 回复数
     *
     * @var int
     */
    public $reply_count;

    /**
     * 点赞数
     *
     * @var int
     */
    public $like_count;

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
        return 'kg_comment';
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
    }

}
