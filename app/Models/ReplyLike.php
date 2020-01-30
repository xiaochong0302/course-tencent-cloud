<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class ReplyLike extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 回复编号
     *
     * @var int
     */
    public $reply_id;

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
        return 'reply_like';
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
