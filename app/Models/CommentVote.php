<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class CommentVote extends Model
{

    /**
     * 投票类型
     */
    const TYPE_AGREE = 1; // 赞成
    const TYPE_OPPOSE = 2; // 反对
    const TYPE_NONE = 3; // 中立

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 评论编号
     *
     * @var int
     */
    public $comment_id;

    /**
     * 用户编号
     *
     * @var int
     */
    public $user_id;

    /**
     * 投票类型
     *
     * @var int
     */
    public $type;

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

    public function getSource()
    {
        return 'kg_comment_vote';
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
