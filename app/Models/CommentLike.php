<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class CommentLike extends Model
{

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
    public $created_at;

    /**
     * 更新时间
     *
     * @var int
     */
    public $updated_at;

    public function getSource()
    {
        return 'comment_like';
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
