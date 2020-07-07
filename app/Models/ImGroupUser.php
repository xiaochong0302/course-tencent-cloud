<?php

namespace App\Models;

class ImGroupUser extends Model
{

    /**
     * 主键编号
     *
     * @var integer
     */
    public $id;

    /**
     * 群组编号
     *
     * @var integer
     */
    public $group_id;

    /**
     * 用户编号
     *
     * @var integer
     */
    public $user_id;

    /**
     * 优先级
     *
     * @var integer
     */
    public $priority;

    /**
     * 屏蔽标识
     *
     * @var integer
     */
    public $blocked;

    /**
     * 创建时间
     *
     * @var integer
     */
    public $create_time;

    /**
     * 更新时间
     *
     * @var integer
     */
    public $update_time;

    public function getSource(): string
    {
        return 'kg_im_group_user';
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

