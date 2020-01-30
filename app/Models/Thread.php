<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Thread extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 作者编号
     *
     * @var int
     */
    public $author_id;

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
     * 标题
     *
     * @var string
     */
    public $title;

    /**
     * 内容
     *
     * @var string
     */
    public $content;

    /**
     * 置顶标识
     *
     * @var int
     */
    public $sticky;

    /**
     * 精华标识
     *
     * @var int
     */
    public $featured;

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
     * 终端IP
     *
     * @var string
     */
    public $client_ip;

    /**
     * 终端类型
     *
     * @var string
     */
    public $client_type;

    /**
     * 最后回复
     *
     * @var int
     */
    public $last_reply;

    /**
     * 回复数量
     *
     * @var int
     */
    public $reply_count;

    /**
     * 创建时间
     *
     * @var int
     */
    public $created_at;

    /**
     * 更新时间
     *
     * @var int
     */
    public $updated_at;

    public function getSource()
    {
        return 'thread';
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
