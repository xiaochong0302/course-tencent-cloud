<?php

namespace App\Models;

class ImFriendUser extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 用户编号
     *
     * @var int
     */
    public $user_id;

    /**
     * 好友编号
     *
     * @var int
     */
    public $friend_id;

    /**
     * 分组编号
     *
     * @var int
     */
    public $group_id;

    /**
     * 消息数量
     *
     * @var int
     */
    public $msg_count;

    /**
     * 屏蔽标识
     *
     * @var int
     */
    public $blocked;

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
        return 'kg_im_friend_user';
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
