<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Comment extends Model
{

    /**
     * 主键编号
     *
     * @var integer
     */
    public $id;

    /**
     * 父级编号
     *
     * @var integer
     */
    public $parent_id;

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
     * 作者编号
     *
     * @var integer
     */
    public $author_id;

    /**
     * 提及用户
     *
     * 数据结构: [{id:123,name:'foo'}]
     *
     * @var string
     */
    public $mentions;

    /**
     * 内容
     *
     * @var string
     */
    public $content;

    /**
     * 回复数
     *
     * @var integer
     */
    public $reply_count;

    /**
     * 点赞数
     *
     * @var integer
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
        return 'comment';
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
