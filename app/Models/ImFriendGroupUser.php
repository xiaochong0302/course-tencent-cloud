<?php

namespace App\Models;

class ImFriendGroupUser extends Model
{

    /**
     * 主键编号
     *
     * @var integer
     */
    public $id;

    /**
     * 分组编号
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

    public function getSource()
    {
        return 'kg_im_friend_group_user';
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

